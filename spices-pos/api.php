<?php
require __DIR__.'/config.php';
date_default_timezone_set('Asia/Colombo');
session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax']);
session_start();
mysqli_report(MYSQLI_REPORT_OFF);
ini_set('display_errors','0');
const SRC=['In-store','Facebook','WhatsApp','TikTok','Instagram','Website'];
const PERMS=['pos','products','analytics','orders','users'];
function out($d,$c=200){http_response_code($c);header('Content-Type: application/json; charset=utf-8');echo json_encode($d,JSON_UNESCAPED_UNICODE);exit;}
function fail($m,$c=400){out(['error'=>$m],$c);}
function q($sql,$t='',...$a){global $db;$s=$db->prepare($sql);if(!$s)fail('DB error: '.$db->error,500);if($t!=='')$s->bind_param($t,...$a);if(!$s->execute())fail('DB error: '.$s->error,500);$r=$s->get_result();return $r?$r->fetch_all(MYSQLI_ASSOC):$s->affected_rows;}
$db=@new mysqli(DB_HOST,DB_USER,DB_PASS);
if($db->connect_error)fail('Cannot connect to MySQL. Start MySQL in the XAMPP control panel.',500);
$db->set_charset('utf8mb4');
if(!@$db->select_db(DB_NAME)||!$db->query("SELECT 1 FROM roles LIMIT 1")){ // first run: create database + tables
  $sql=@file_get_contents(__DIR__.'/database.sql');if(!$sql)fail('database.sql is missing',500);
  if(!$db->multi_query(str_replace('spice_pos',DB_NAME,$sql)))fail('Install failed: '.$db->error,500);
  do{if($r=$db->store_result())$r->free();}while($db->more_results()&&$db->next_result());
  if($db->errno)fail('Install failed: '.$db->error,500);
  $db->select_db(DB_NAME);
}
$db->query("SET time_zone='+05:30'");
if(!q("SELECT 1 FROM users LIMIT 1"))foreach([['admin','Shop Owner','admin123','Admin'],['cashier','Cashier 1','cash123','Cashier']] as $u)
  q("INSERT INTO users(username,full_name,pass_hash,role_id) SELECT ?,?,?,id FROM roles WHERE name=?",'ssss',$u[0],$u[1],password_hash($u[2],PASSWORD_DEFAULT),$u[3]);

function me(){if(empty($_SESSION['uid']))return null;$r=q("SELECT u.id,u.username,u.full_name,r.name role,r.perms FROM users u JOIN roles r ON r.id=u.role_id WHERE u.id=?",'i',$_SESSION['uid']);return $r?$r[0]:null;}
function need($p){$m=me();if(!$m)fail('Please log in',401);if(!in_array($p,explode(',',$m['perms'])))fail('Permission denied',403);return $m;}
function throttle(){$ip=$_SERVER['REMOTE_ADDR']??'';if(q("SELECT COUNT(*) c FROM login_attempts WHERE ip=? AND ts>DATE_SUB(NOW(),INTERVAL 10 MINUTE)",'s',$ip)[0]['c']>=8)fail('Too many attempts. Try again in 10 minutes.',429);}
function miss(){q("INSERT INTO login_attempts(ip) VALUES(?)",'s',$_SERVER['REMOTE_ADDR']??'');}
function prods(){$r=q("SELECT id,code,name,category,price,unit,stock,image,emoji,color FROM products WHERE active=1 ORDER BY id");foreach($r as &$p){$p['id']=(int)$p['id'];$p['price']=(float)$p['price'];$p['stock']=(int)$p['stock'];}return $r;}
function sales($id=0){$w=$id?"WHERE id=".(int)$id:"";
  $r=q("SELECT id,UNIX_TIMESTAMP(created_at)*1000 t,channel src,customer cust,status st,discount disc,total,payment pay,paid,staff `by` FROM sales $w ORDER BY id DESC LIMIT 500");
  if(!$r)return [];$ids=implode(',',array_map(fn($x)=>(int)$x['id'],$r));$m=[];
  foreach(q("SELECT sale_id,product_id id,product_name name,qty q,unit_price price FROM sale_items WHERE sale_id IN($ids)") as $i)$m[(int)$i['sale_id']][]=['id'=>(int)$i['id'],'name'=>$i['name'],'q'=>(int)$i['q'],'price'=>(float)$i['price']];
  foreach($r as &$s){$s['id']=(int)$s['id'];$s['t']=(int)$s['t'];foreach(['disc','total','paid']as$k)$s[$k]=(float)$s[$k];$s['items']=$m[$s['id']]??[];}return $r;}
function blends(){global $db;
  $db->query("CREATE TABLE IF NOT EXISTS spice_blends(id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, total_weight_g DECIMAL(10,2) NOT NULL DEFAULT 0, pack_unit VARCHAR(20) NOT NULL DEFAULT '100g', total_cost DECIMAL(10,2) NOT NULL DEFAULT 0, cost_per_unit DECIMAL(10,2) NOT NULL DEFAULT 0, selling_price DECIMAL(10,2) NOT NULL DEFAULT 0, profit_amount DECIMAL(10,2) NOT NULL DEFAULT 0, margin_pct DECIMAL(6,2) NOT NULL DEFAULT 0, ingredients MEDIUMTEXT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB");
  $r=q("SELECT id, name, total_weight_g, pack_unit, total_cost, cost_per_unit, selling_price, profit_amount, margin_pct, ingredients, UNIX_TIMESTAMP(created_at)*1000 t FROM spice_blends ORDER BY id DESC LIMIT 100");
  if(!$r)return [];
  foreach($r as &$b){
    $b['id']=(int)$b['id'];$b['t']=(int)$b['t'];$b['total_weight_g']=(float)$b['total_weight_g'];
    $b['total_cost']=(float)$b['total_cost'];$b['cost_per_unit']=(float)$b['cost_per_unit'];
    $b['selling_price']=(float)$b['selling_price'];$b['profit_amount']=(float)$b['profit_amount'];
    $b['margin_pct']=(float)$b['margin_pct'];
    $b['ingredients']=json_decode($b['ingredients']??'[]',true)?:[];
  }
  return $r;
}
function createSale($items,$src,$cust,$disc,$pay,$paid,$status,$staff){global $db;
  if(!is_array($items)||!$items||count($items)>100)fail('Cart is empty');
  if(!in_array($src,SRC,true))$src='In-store';if(!in_array($pay,['Cash','Card','Online'],true))$pay='Cash';
  $db->begin_transaction();$lines=[];$sub=0;
  foreach($items as $it){$id=(int)($it['id']??0);$n=(int)($it['q']??0);if($n<1)continue;
    $p=q("SELECT name,price FROM products WHERE id=? AND active=1",'i',$id);if(!$p){$db->rollback();fail('Product not found');}
    if(q("UPDATE products SET stock=stock-? WHERE id=? AND stock>=?",'iii',$n,$id,$n)<1){$db->rollback();fail('Not enough stock for '.$p[0]['name']);}
    $lines[]=[$id,$p[0]['name'],$n,(float)$p[0]['price']];$sub+=$n*(float)$p[0]['price'];}
  if(!$lines){$db->rollback();fail('Cart is empty');}
  $disc=max(0,min((float)$disc,$sub));$tot=$sub-$disc;$paid=(float)$paid;
  if($pay==='Cash'&&$paid>0&&$paid<$tot){$db->rollback();fail('Cash received is less than the total');}
  if(!($pay==='Cash'&&$paid>0))$paid=$tot;
  q("INSERT INTO sales(channel,customer,status,discount,total,payment,paid,staff) VALUES(?,?,?,?,?,?,?,?)",'sssddsds',$src,mb_substr((string)$cust,0,150),$status,$disc,$tot,$pay,$paid,$staff);
  $sid=$db->insert_id;
  foreach($lines as $l)q("INSERT INTO sale_items(sale_id,product_id,product_name,qty,unit_price) VALUES(?,?,?,?,?)",'iisid',$sid,$l[0],$l[1],$l[2],$l[3]);
  $db->commit();return sales($sid)[0];}

$a=$_GET['a']??'';$in=[];
if($_SERVER['REQUEST_METHOD']==='POST'){if(($_SERVER['HTTP_X_REQUESTED_WITH']??'')!=='fetch')fail('Bad request');$in=json_decode(file_get_contents('php://input'),true);if(!is_array($in))$in=[];}
switch($a){
case 'captcha':
  $c='';for($i=0;$i<5;$i++)$c.='ABCDEFGHJKMNPRSTUVWXYZ23456789'[random_int(0,29)];$_SESSION['cap']=$c;
  $im=imagecreatetruecolor(150,44);imagefill($im,0,0,imagecolorallocate($im,245,238,228));
  for($i=0;$i<7;$i++)imageline($im,rand(0,150),rand(0,44),rand(0,150),rand(0,44),imagecolorallocate($im,rand(150,210),rand(120,180),rand(100,160)));
  for($i=0;$i<5;$i++)imagestring($im,5,14+$i*26+rand(-2,2),rand(8,18),$c[$i],imagecolorallocate($im,rand(0,120),rand(0,80),rand(0,60)));
  header('Content-Type: image/png');header('Cache-Control: no-store');imagepng($im);exit;
case 'public': out(['products'=>prods()]);
case 'state':
  $m=me();if(!$m)out(['me'=>null]);$pm=explode(',',$m['perms']);$roles=[];
  foreach(q("SELECT name,perms FROM roles ORDER BY id")as$r)$roles[$r['name']]=explode(',',$r['perms']);
  $o=['me'=>['u'=>$m['username'],'n'=>$m['full_name'],'r'=>$m['role']],'roles'=>$roles,'products'=>prods(),'sales'=>[],'users'=>[],'blends'=>blends()];
  if(array_intersect(['analytics','orders'],$pm))$o['sales']=sales();
  if(in_array('users',$pm))$o['users']=q("SELECT u.username u,u.full_name n,r.name r FROM users u JOIN roles r ON r.id=u.role_id ORDER BY u.id");
  $o['sq']=(int)q("SELECT COUNT(*) c FROM security_answers WHERE user_id=?",'i',$m['id'])[0]['c']===3;out($o);
case 'login':
  throttle();
  $u=q("SELECT id,pass_hash FROM users WHERE username=?",'s',(string)($in['u']??''));
  if(!$u||!password_verify((string)($in['p']??''),$u[0]['pass_hash'])){miss();fail('Invalid username or password',401);}
  session_regenerate_id(true);$_SESSION['uid']=(int)$u[0]['id'];out(['ok'=>1]);
case 'logout': session_destroy();out(['ok'=>1]);
case 'sq_get':
  throttle();$r=q("SELECT s.question_idx qi FROM security_answers s JOIN users u ON u.id=s.user_id WHERE u.username=? ORDER BY s.slot",'s',(string)($in['u']??''));
  if(count($r)!==3){miss();fail('Security questions are not set for this account. Ask the shop owner to help.');}
  out(['q'=>array_map(fn($x)=>(int)$x['qi'],$r)]);
case 'reset':
  throttle();$np=(string)($in['np']??'');$ans=$in['a']??[];
  $r=q("SELECT u.id,s.answer_hash FROM users u JOIN security_answers s ON s.user_id=u.id WHERE u.username=? ORDER BY s.slot",'s',(string)($in['u']??''));
  $ok=count($r)===3&&is_array($ans);if($ok)foreach($r as $i=>$x)if(!password_verify(strtolower(trim((string)($ans[$i]??''))),$x['answer_hash']))$ok=false;
  if(!$ok){miss();fail('Answers do not match');}if(strlen($np)<6)fail('Password must be at least 6 characters');
  q("UPDATE users SET pass_hash=? WHERE id=?",'si',password_hash($np,PASSWORD_DEFAULT),$r[0]['id']);out(['ok'=>1]);
case 'sq_set':
  $m=me();$qs=$in['q']??[];
  if(!$m)fail('Please log in',401);if(count($qs)!==3||count(array_unique(array_column($qs,0)))!==3)fail('Pick 3 different questions');
  q("DELETE FROM security_answers WHERE user_id=?",'i',$m['id']);
  foreach(array_values($qs)as$i=>$x){$an=strtolower(trim((string)$x[1]));$qi=(int)$x[0];if($an===''||$qi<0||$qi>5)fail('Answer all questions');
    q("INSERT INTO security_answers(user_id,slot,question_idx,answer_hash) VALUES(?,?,?,?)",'iiis',$m['id'],$i,$qi,password_hash($an,PASSWORD_DEFAULT));}out(['ok'=>1]);
case 'pw_change':
  $m=me();if(!$m)fail('Please log in',401);$np=(string)($in['np']??'');
  $h=q("SELECT pass_hash FROM users WHERE id=?",'i',$m['id'])[0]['pass_hash'];
  if(!password_verify((string)($in['op']??''),$h))fail('Current password is wrong');if(strlen($np)<6)fail('Password must be at least 6 characters');
  q("UPDATE users SET pass_hash=? WHERE id=?",'si',password_hash($np,PASSWORD_DEFAULT),$m['id']);out(['ok'=>1]);
case 'product_add':
  need('products');$n=trim((string)($in['name']??''));$pr=(float)($in['price']??0);$img=$in['image']??null;
  if($n===''||mb_strlen($n)>100||$pr<=0)fail('Name and a valid price are required');
  if($img!==null&&(!is_string($img)||strncmp($img,'data:image/',11)||strlen($img)>400000))fail('Image is invalid or too large');
  q("INSERT INTO products(name,price,unit,stock,image) VALUES(?,?,?,?,?)",'sdsis',$n,$pr,mb_substr(trim((string)($in['unit']??'unit'))?:'unit',0,20),max(0,(int)($in['stock']??0)),$img);
  $id=$db->insert_id;q("UPDATE products SET code=? WHERE id=?",'si',sprintf('SP%03d',$id),$id);out(['id'=>$id]);
case 'product_stock': need('products');$n=(int)($in['add']??0);if($n<1)fail('Enter a positive number');q("UPDATE products SET stock=stock+? WHERE id=?",'ii',$n,(int)$in['id']);out(['ok'=>1]);
case 'product_del': need('products');q("UPDATE products SET active=0,code=NULL WHERE id=?",'i',(int)$in['id']);out(['ok'=>1]);
case 'sale': $m=need('pos');out(['sale'=>createSale($in['items']??[],(string)($in['src']??''),$in['cust']??'',$in['disc']??0,(string)($in['pay']??''),$in['paid']??0,'done',$m['username'])]);
case 'sale_status': need('orders');q("UPDATE sales SET status=IF(status='done','pending','done') WHERE id=?",'i',(int)$in['id']);out(['ok'=>1]);
case 'role_perm':
  need('users');$r=(string)($in['role']??'');$p=(string)($in['perm']??'');if($r==='Admin'||!in_array($p,PERMS,true))fail('Not allowed');
  $row=q("SELECT perms FROM roles WHERE name=?",'s',$r);if(!$row)fail('Role not found');$pm=array_filter(explode(',',$row[0]['perms']));
  $pm=!empty($in['on'])?array_unique(array_merge($pm,[$p])):array_diff($pm,[$p]);q("UPDATE roles SET perms=? WHERE name=?",'ss',implode(',',$pm),$r);out(['ok'=>1]);
case 'role_add': need('users');$n=trim((string)($in['name']??''));if($n===''||mb_strlen($n)>40)fail('Invalid role name');if(q("SELECT 1 FROM roles WHERE name=?",'s',$n))fail('Role exists');q("INSERT INTO roles(name,perms) VALUES(?,'pos')",'s',$n);out(['ok'=>1]);
case 'user_role': need('users');$u=(string)($in['u']??'');if($u==='admin')fail('The admin account always keeps the Admin role');
  if(!q("SELECT 1 FROM roles WHERE name=?",'s',(string)($in['r']??'')))fail('Role not found');
  q("UPDATE users SET role_id=(SELECT id FROM roles WHERE name=?) WHERE username=?",'ss',(string)$in['r'],$u);out(['ok'=>1]);
case 'user_del': $m=need('users');$u=(string)($in['u']??'');if($u==='admin'||$u===$m['username'])fail('This account cannot be deleted');q("DELETE FROM users WHERE username=?",'s',$u);out(['ok'=>1]);
case 'user_add':
  need('users');$u=(string)($in['u']??'');$p=(string)($in['p']??'');
  if(!preg_match('/^[A-Za-z0-9_.-]{3,30}$/',$u))fail('Username: 3-30 letters, numbers, . _ -');if(strlen($p)<6)fail('Password must be at least 6 characters');
  if(q("SELECT 1 FROM users WHERE username=?",'s',$u))fail('Username already exists');
  if(!q("INSERT INTO users(username,full_name,pass_hash,role_id) SELECT ?,?,?,id FROM roles WHERE name=?",'ssss',$u,mb_substr(trim((string)($in['n']??''))?:$u,0,80),password_hash($p,PASSWORD_DEFAULT),(string)($in['r']??'')))fail('Role not found');out(['ok'=>1]);
case 'blend_save':
  $m=need('products');
  $n=trim((string)($in['name']??''));
  if($n==='')fail('Recipe or blend name is required');
  $tw=max(0,(float)($in['total_weight_g']??0));
  $pu=mb_substr(trim((string)($in['pack_unit']??'100g'))?:'100g',0,20);
  $tc=max(0,(float)($in['total_cost']??0));
  $cpu=max(0,(float)($in['cost_per_unit']??0));
  $sp=max(0,(float)($in['selling_price']??0));
  $pa=(float)($in['profit_amount']??($sp-$cpu));
  $mp=(float)($in['margin_pct']??0);
  $ing=json_encode($in['ingredients']??[],JSON_UNESCAPED_UNICODE);
  blends();
  q("INSERT INTO spice_blends(name,total_weight_g,pack_unit,total_cost,cost_per_unit,selling_price,profit_amount,margin_pct,ingredients) VALUES(?,?,?,?,?,?,?,?,?)",'sdsddddds',$n,$tw,$pu,$tc,$cpu,$sp,$pa,$mp,$ing);
  out(['id'=>$db->insert_id,'ok'=>1]);
case 'blend_del':
  $m=need('products');
  $id=(int)($in['id']??0);
  q("DELETE FROM spice_blends WHERE id=?",'i',$id);
  out(['ok'=>1]);
default: fail('Unknown action',404);
}
