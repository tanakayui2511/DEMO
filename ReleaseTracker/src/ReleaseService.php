<?php
declare(strict_types=1);require_once __DIR__.'/App.php';
final class ReleaseService {
 public static function check(PgSql\Connection $c,int $releaseId,int $projectId): array {
  $r=queryParams($c,'SELECT id,version_name,status FROM releases WHERE id=$1 AND project_id=$2',[(string)$releaseId,(string)$projectId]);$rel=pg_fetch_assoc($r);if(!$rel)throw new RuntimeException('Không tìm thấy phiên bản trong dự án.');
  $items=pg_fetch_all(queryParams($c,'SELECT i.id,i.title,i.status,i.priority FROM release_items ri JOIN issues i ON i.id=ri.issue_id WHERE ri.release_id=$1 AND i.project_id=$2',[(string)$releaseId,(string)$projectId]))?:[];$problems=[];
  if(!$items)$problems[]='Phiên bản chưa có công việc nào.';
  foreach($items as $i){if($i['priority']==='blocker'){$t=queryParams($c,'SELECT result FROM test_results WHERE issue_id=$1 ORDER BY id DESC LIMIT 1',[(string)$i['id']]);$last=pg_num_rows($t) ? pg_fetch_result($t,0,0) : null;if($last!=='passed')$problems[]='Lỗi blocker #'.$i['id'].' chưa có kết quả kiểm thử lại đạt: '.$i['title'];}
   $deps=pg_fetch_all(queryParams($c,"SELECT b.id,b.title,b.status FROM dependencies d JOIN issues a ON a.id=d.issue_id JOIN issues b ON b.id=d.depends_on_id WHERE a.id=$1 AND b.status NOT IN ('verified','closed')",[(string)$i['id']]))?:[];foreach($deps as $d)$problems[]='Công việc #'.$i['id'].' bị chặn bởi #'.$d['id'].' '.$d['title'];
  }
  return ['release'=>$rel,'items'=>$items,'problems'=>array_values(array_unique($problems))];
 }
 public static function snapshot(PgSql\Connection $c,int $releaseId,int $projectId,int $userId): void {
  $check=self::check($c,$releaseId,$projectId);if($check['problems'])throw new RuntimeException(implode("\n",$check['problems']));if($check['release']['status']!=='planned')throw new RuntimeException('Phiên bản đã chốt hoặc không còn ở trạng thái dự kiến.');
  pg_query($c,'BEGIN');try{
   queryParams($c,"UPDATE release_items ri SET snapshot_title=i.title,snapshot_status=i.status,snapshot_priority=i.priority,snapshot_assignee=COALESCE(u.full_name,''),snapshot_description=i.description,snapshot_test_result=COALESCE((SELECT tr.result FROM test_results tr WHERE tr.issue_id=i.id ORDER BY tr.id DESC LIMIT 1),'not_tested') FROM issues i LEFT JOIN users u ON u.id=i.assignee_id WHERE ri.release_id=$1 AND ri.issue_id=i.id",[(string)$releaseId]);
   queryParams($c,"UPDATE releases SET status='released',checked_at=now(),released_at=now() WHERE id=$1 AND project_id=$2 AND status='planned'",[(string)$releaseId,(string)$projectId]);
   audit($c,$projectId,$userId,'release_snapshotted','release',$releaseId,['item_count'=>count($check['items'])]);pg_query($c,'COMMIT');
  }catch(Throwable $e){pg_query($c,'ROLLBACK');throw $e;}
 }
}
