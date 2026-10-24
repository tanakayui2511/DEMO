<?php
declare(strict_types=1);
require_once __DIR__.'/App.php';
final class DependencyService {
 public static function path(PgSql\Connection $c,int $start,int $target,int $projectId): ?array {
  $adj=[];$r=queryParams($c,'SELECT d.issue_id,d.depends_on_id FROM dependencies d JOIN issues a ON a.id=d.issue_id JOIN issues b ON b.id=d.depends_on_id WHERE a.project_id=$1 AND b.project_id=$1',[(string)$projectId]);
  while($e=pg_fetch_assoc($r))$adj[(int)$e['issue_id']][]=(int)$e['depends_on_id'];
  $stack=[[$start,[$start]]];$seen=[];while($stack){[$node,$path]=array_pop($stack);if($node===$target)return $path;if(isset($seen[$node]))continue;$seen[$node]=true;foreach($adj[$node]??[] as $next)if(!isset($seen[$next]))$stack[]=[$next,[...$path,$next]];}return null;
 }
 public static function add(PgSql\Connection $c,int $projectId,int $issueId,int $dependsOn): void {
  if($issueId===$dependsOn)throw new RuntimeException('Một công việc không thể phụ thuộc chính nó.');
  $r=queryParams($c,'SELECT count(*) FROM issues WHERE project_id=$1 AND id IN ($2,$3)',[(string)$projectId,(string)$issueId,(string)$dependsOn]);if((int)pg_fetch_result($r,0,0)!==2)throw new RuntimeException('Hai công việc phải thuộc cùng dự án.');
  $path=self::path($c,$dependsOn,$issueId,$projectId);if($path!==null){$path[]=$dependsOn;throw new RuntimeException('Từ chối quan hệ vòng: '.implode(' → ',array_map(fn($id)=>'#'.$id,$path)));}
  queryParams($c,'INSERT INTO dependencies(issue_id,depends_on_id) VALUES($1,$2) ON CONFLICT DO NOTHING',[(string)$issueId,(string)$dependsOn]);
 }
}
