<div class="tai_header-bot  scrollTop ">
    <div class="wrapper">
        <div class="account-box">
			<form method="POST" action="{{ route('member.login.post') }}">
                <input type="text" id="login_account" placeholder="账号" class="username" required name="name">
                <input type="password" id="login_password" placeholder="密码" class="psw" required name="password">
                <div class="check-code-wrapper">
                    <input type="text" placeholder="请输入验证码" required name="captcha" onfocus="re_captcha_re();">
                    <img onclick="javascript:re_captcha_re();" src="kit/captcha/1" tppabs="http://mb3.uc697.com/kit/captcha/1"
                         id="c2c98f0de5a04167a9e427d883690ff11"
                    style="display: inline-block;width: 80px;">
                    <script>
                        function re_captcha_re() {
                            $url = "http://mb3.uc697.com/kit/captcha";
                            $url = $url + "/" + Math.random();
                            document.getElementById('c2c98f0de5a04167a9e427d883690ff11').src=$url;
                        }
                    </script>
                </div>
                <button class="login-box modal-login_submit ajax-submit-btn" type="button">立即登录</button>
<!--                     <a href="r.htm"  class="join-btn">免费开户</a> -->
            </form>
    	</div>
    </div>
</div>