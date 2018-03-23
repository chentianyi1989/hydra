<div class="notice-row">
    <div class="noticeBox">
      <div class="w">
        <div class="title">
          最新公告：
        </div>
        <div class="bd2">
          <div id="memberLatestAnnouncement" style="cursor:pointer;color:#fff;">
            <marquee id="mar0" scrollamount="3" scrolldelay="100" direction="left"
                     onmouseover="this.stop();" onmouseout="this.start();">
                     
                     @foreach($system_notices as $v)
                        <span>
                            <h4>{{ $v->title }}</h4>
                            <p>✿{{ $v->content }}</p>
                        </span>
                     @endforeach
                              
                              
                              
                              
                <span>QQ:2697173363、2697173363</span>
                          </marquee>
          </div>
        </div>
      </div>
    </div>
  </div>