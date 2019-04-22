<br>
<div>
    <a class="btn btn-block btn-outline-info" href="{{ url('/Panel/Admin/Statistics/1') }}">
        Number of Student in level
    </a>

    <br>

    <a class="btn btn-block btn-outline-info" href="{{ url('/Panel/Admin/Statistics/2') }}">
        Number of Registered student
    </a>

    <br>

    <a class="btn btn-block btn-outline-info" href="{{ url('/Panel/Admin/Statistics/3') }}">
        Last Semester Result
    </a>

    <br>

    <a class="btn btn-block btn-outline-info" href="{{ url('/Panel/Admin/Statistics/4') }}">
        Top 10 GPA in Each Level
    </a>

    <br>
</div>

<br>

<div id="portalStatistics"></div>

@if($statistics === 1)
    <?= $lava->render('ColumnChart', 'studentNumber', 'portalStatistics') ?>
@elseif($statistics === 2)
    @if($lava != null)
          <?= $lava->render('ColumnChart', 'studentNumber', 'portalStatistics') ?>
    @else
        <div>
            <ul>
                    <li class="alert alert-danger"> No Data to visualize
                        <i class="fa fa-times" style="float: right "></i>
                    </li>
            </ul>
        </div>
    @endif

@elseif($statistics === 3)
    @if($openCourse->count() > 0)
        <?= $lava->render('ColumnChart', 'studentNumber', 'portalStatistics') ?>
    @else
        <div>
            <ul>
                <li class="alert alert-danger"> No Data to visualize
                    <i class="fa fa-times" style="float: right "></i>
                </li>
            </ul>
        </div>
    @endif
@elseif($statistics === 4)
    <?= $lava->render('LineChart', 'studentNumber', 'portalStatistics') ?>
@endif
