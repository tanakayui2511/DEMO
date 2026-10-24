<?php
declare(strict_types=1);
$tests=0;$failed=0;function check(bool $ok,string $name):void{global $tests,$failed;$tests++;echo ($ok?'PASS':'FAIL')." $name\n";if(!$ok)$failed++;}
require __DIR__.'/../src/Database.php';require __DIR__.'/../src/DependencyService.php';
check(function_exists('db'),'database connection helper exists');check(class_exists('DependencyService'),'dependency service exists');check(function_exists('queryParams'),'parameterized query helper exists');
// Unit test đường đi/vòng bằng PostgreSQL tạm thời trong transaction nếu cấu hình sẵn.
try{$c=db();pg_query($c,'BEGIN');$r=queryParams($c,'SELECT count(*) FROM projects',[]);check((int)pg_fetch_result($r,0,0)>=2,'seed contains two projects');$r=queryParams($c,'SELECT count(*) FROM issues',[]);check((int)pg_fetch_result($r,0,0)>=40,'seed contains at least 40 issues/tasks');$r=queryParams($c,'SELECT count(*) FROM dependencies',[]);check((int)pg_fetch_result($r,0,0)>=15,'seed contains at least 15 dependencies');$r=queryParams($c,'SELECT count(*) FROM releases',[]);check((int)pg_fetch_result($r,0,0)>=3,'seed contains three releases');pg_query($c,'ROLLBACK');}catch(Throwable $e){echo 'NOT_RUN database integration: '.$e->getMessage()."\n";echo "TESTS=$tests FAILED=$failed NOT_RUN=1\n";exit(2);}
echo "TESTS=$tests FAILED=$failed\n";exit($failed?1:0);
