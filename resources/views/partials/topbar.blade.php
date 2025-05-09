<nav class="navbar navbar-expand-lg navbar-dark bg-light bg-gradient sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="/" style="font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;Color:black;">
            <h1>AAS</h1>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 fs-5">
                <li class="nav-item d-flex">
                    <a class="nav-link text-dark text-nowrap" href="/dashboard/{{date('m')}}/{{date('d')}}">Dashboard</a>
                    <a class="nav-link text-dark text-nowrap" href="/classroom">Classroom</a>
                    <a class="nav-link text-dark text-nowrap" href="/course">Course</a>
                    <a class="nav-link text-dark text-nowrap" href="/users">User</a>
                    <a class="nav-link text-dark text-nowrap" href="/timetable">Timetable</a>
                    <a class="nav-link text-dark text-nowrap" href="/timesheet?month={{date('m')}}">Timesheet</a>
                </li>
            </ul>

            <ul class="navbar-nav mb-lg-0 fs-5">
                <div class="btn-group">
                    <div class="btn-group dropstart" role="group">
                      <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split fs-4" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="visually-hidden">Toggle Dropstart</span>
                      </button>
                      <ul class="dropdown-menu">
                        <a class="inbox nav-link text-dark text-nowrap" href="/notifications">Inbox</a>
                        <a class="read nav-link text-dark text-nowrap" href="/notifications?status=read">Read Messages</a>
                        <a class="draft nav-link text-dark text-nowrap" href="/notifications?status=draft">Draft</a>
                        <a class="sent nav-link text-dark text-nowrap" href="/notifications?status=sent">Sent</a>
                        <hr>
                        <p class="nav-link text-dark text-nowrap text-muted">Counts are added with replies</p>
                      </ul>
                    </div>
                    <button type="button" class="btn btn-light">
                        <a class="nav-link text-dark text-nowrap fs-4 p-0 m-0" href="/notifications?status=unread"><i class="fa fa-envelope"></i></a>
                    </button>
                </div>
                <a href="/logout" class="nav-link text-dark text-nowrap mt-2">Logout</a>
            </ul>
        </div>
    </div>
</nav>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        var inbox = $(".inbox");
        var read = $(".read");
        var draft = $(".draft");
        var sent = $(".sent");

        function checkNotifications() {
            $.ajax({
                url: "/notifications/count",
                method: "GET",
                dataType: "json",
                success: function (data) {
                    if (data.unread > 0) {
                        $(".fa-envelope").append('<span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle"></span>');
                    } else {
                        $(".fa-envelope span").remove();
                    }

                    inbox.html("Inbox (" + data.inbox + ")");
                    read.html("Read Messages (" + data.read + ")");
                    draft.html("Draft (" + data.draft + ")");
                    sent.html("Sent (" + data.sent + ")");

                    setTimeout(function(){
                        checkNotifications();
                    }, 30000);
                },
                error: function () {
                    console.error("Failed to fetch notifications");
                    setTimeout(function(){
                        checkNotifications();
                    }, 30000);
                }
            });
        }

        checkNotifications();
});
</script>
