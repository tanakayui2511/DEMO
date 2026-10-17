const issues = [
  { id: 'BUG-124', title: 'Không thể đăng nhập bằng tài khoản Google', priority: 'Cao', status: 'Đang xử lý', assignee: 'Nguyễn Minh', initials: 'NM', color: 'avatar-minh', updated: '10 phút trước' },
  { id: 'BUG-123', title: 'Video bài giảng bị dừng khi chuyển tab', priority: 'Trung bình', status: 'Đang kiểm thử', assignee: 'Hoàng Phúc', initials: 'HP', color: 'avatar-hoa', updated: '35 phút trước' },
  { id: 'BUG-122', title: 'Sai định dạng ngày trong lịch học', priority: 'Thấp', status: 'Đã giải quyết', assignee: 'Trần Anh', initials: 'TA', color: 'avatar-tuan', updated: '2 giờ trước' },
  { id: 'BUG-121', title: 'Thông báo nộp bài không hiển thị trên mobile', priority: 'Cao', status: 'Đang mở', assignee: 'Linh Trần', initials: 'LT', color: 'avatar-linh', updated: 'Hôm qua' },
  { id: 'BUG-120', title: 'Không tải được file đính kèm dung lượng lớn', priority: 'Trung bình', status: 'Đang mở', assignee: 'Nguyễn Minh', initials: 'NM', color: 'avatar-minh', updated: 'Hôm qua' }
];
const activities = [
  ['✓', '<b>Trần Anh</b> đã giải quyết lỗi <b>BUG-122</b>', '2 giờ trước'],
  ['◉', '<b>Nguyễn Minh</b> đã chuyển <b>BUG-124</b> sang Đang xử lý', '3 giờ trước'],
  ['◈', '<b>Hoàng Phúc</b> đã tạo bản phát hành <b>v2.4.0</b>', '5 giờ trước'],
  ['+', '<b>Linh Trần</b> đã tạo lỗi mới <b>BUG-121</b>', 'Hôm qua']
];
const releases = [
  {v:'v2.4.0', title:'Tính năng học trực tuyến', text:'Lớp học trực tiếp, chia sẻ màn hình và bảng trắng.', date:'25 tháng 10, 2024', progress:72, status:'Đang phát triển'},
  {v:'v2.3.2', title:'Cải tiến trải nghiệm học tập', text:'Tối ưu hiệu suất và khắc phục các lỗi quan trọng.', date:'05 tháng 11, 2024', progress:38, status:'Đang lên kế hoạch'},
  {v:'v2.3.1', title:'Bản vá bảo mật', text:'Cập nhật bảo mật và cải thiện độ ổn định hệ thống.', date:'15 tháng 10, 2024', progress:100, status:'Đã phát hành'}
];
const team = [{name:'Linh Trần',role:'Project manager',i:'LT',c:'avatar-linh'},{name:'Nguyễn Minh',role:'Frontend developer',i:'NM',c:'avatar-minh'},{name:'Hoàng Phúc',role:'Backend developer',i:'HP',c:'avatar-hoa'},{name:'Trần Anh',role:'QA Engineer',i:'TA',c:'avatar-tuan'}];
const $ = s => document.querySelector(s);
function badge(text, type) { return `<span class="tag ${type}">${text}</span>`; }
function renderIssues() {
  const term = $('#issueSearch').value.toLowerCase(), status = $('#statusFilter').value;
  const shown = issues.filter(x => (status === 'all' || x.status === status) && (x.title.toLowerCase().includes(term) || x.id.toLowerCase().includes(term)));
  $('#issuesTable').innerHTML = shown.map(x => `<tr><td class="issue-code">${x.id}</td><td class="issue-title">${x.title}</td><td>${badge(x.priority, `priority-${x.priority === 'Cao' ? 'high' : x.priority === 'Trung bình' ? 'medium' : 'low'}`)}</td><td>${badge(x.status, `status-${({'Đang mở':'open','Đang xử lý':'progress','Đang kiểm thử':'testing','Đã giải quyết':'done'})[x.status]}`)}</td><td><div class="assignee"><div class="avatar ${x.color}">${x.initials}</div>${x.assignee}</div></td><td>${x.updated}</td></tr>`).join('') || `<tr><td colspan="6" style="text-align:center;padding:35px">Không tìm thấy lỗi phù hợp.</td></tr>`;
}
function render() {
  $('#activityList').innerHTML = activities.map(a => `<div class="activity"><div class="activity-icon">${a[0]}</div><div><p>${a[1]}</p><small>${a[2]}</small></div></div>`).join('');
  $('#releaseCards').innerHTML = releases.map(r => `<article class="panel release-card"><span class="release-badge">${r.v}</span><h2>${r.title}</h2><p>${r.text}</p><div class="release-meta">◷ &nbsp; Dự kiến: ${r.date}</div><div class="progress-block"><div><span>Tiến độ</span><b>${r.progress}%</b></div><div class="progress"><i style="width:${r.progress}%"></i></div></div><footer><span>${r.status}</span><button class="text-button">Chi tiết →</button></footer></article>`).join('');
  $('#teamGrid').innerHTML = team.map(t => `<article class="panel"><div class="avatar ${t.c}">${t.i}</div><div><strong>${t.name}</strong><small>${t.role}</small></div></article>`).join('');
  renderIssues();
}
function pageName(id) { return {'dashboard':'Tổng quan','issues':'Theo dõi lỗi','releases':'Phát hành','roadmap':'Lộ trình','team':'Thành viên'}[id]; }
function showPage(id) { document.querySelectorAll('.page').forEach(p => p.classList.toggle('active', p.id === id)); document.querySelectorAll('.nav-item').forEach(b => b.classList.toggle('active', b.dataset.page === id)); $('#crumb').textContent = pageName(id); window.scrollTo({top:0,behavior:'smooth'}); }
document.querySelectorAll('[data-page]').forEach(b => b.addEventListener('click', () => showPage(b.dataset.page)));
$('#issueSearch').addEventListener('input', renderIssues); $('#statusFilter').addEventListener('change', renderIssues);
const modal = $('#modal'); function openModal(){modal.classList.add('open'); $('#newIssueTitle').focus()} function closeModal(){modal.classList.remove('open')}
$('#newIssueBtn').onclick = openModal; $('#newIssueBtn2').onclick = openModal; $('#closeModal').onclick = closeModal; $('#cancelModal').onclick = closeModal;
modal.addEventListener('click', e => {if(e.target === modal) closeModal()});
$('#issueForm').addEventListener('submit', e => {e.preventDefault(); const title=$('#newIssueTitle').value.trim(); if(!title)return; const pri=$('#newIssuePriority').value, name=$('#newIssueAssignee').value; const t=team.find(x=>x.name===name)||team[0]; issues.unshift({id:`BUG-${125 + issues.length}`,title,priority:pri,status:'Đang mở',assignee:name,initials:t.i,color:t.c,updated:'Vừa xong'}); $('#issueCount').textContent=12+issues.length-5; activities.unshift(['+','<b>Linh Trần</b> đã tạo lỗi mới <b>'+issues[0].id+'</b>','Vừa xong']); $('#issueForm').reset(); closeModal(); render(); showPage('issues');});
render();
