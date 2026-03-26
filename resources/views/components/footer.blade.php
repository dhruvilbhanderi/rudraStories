<div class="help" id="support-chat-widget" data-ws-url="{{ env('CHAT_WS_URL', 'ws://127.0.0.1:6001') }}">
  <div class="helpque " id="helpque10">
    <div class="hd">
      <h4>Support Chat</h4>
    </div>
    <div style="margin:6px 0 10px;">
      <a href="/support-chat" style="font-weight:600;color:#1d3557;text-decoration:underline;">Open Full Support Chat Page</a>
    </div>
    <div id="chat-history" style="max-height:240px;overflow-y:auto;background:#fff;padding:8px;border:1px solid #ddd;margin-bottom:10px;"></div>
    <form action="" method="post" id="hlpfr252" enctype="multipart/form-data">
      <label for="">Name :</label>
      <input type="text" name="nm45226" id="chatName">
      <label for="">Email :</label>
      <input type="text" name="em45226" id="chatEmail">
      @csrf
      <label for="">Message :</label>
      <textarea name="msg45226" id="chatMessage" cols="30" rows="4"></textarea>
      <label for="">File :</label>
      <input type="file" name="chat_file" id="chatFile">
      <input type="submit" name="sub120" value="SEND" id="s55214" class="ms45snd">
    </form>
  </div>
  <div class="helpic" id="ophl22">
    <i class="fa fa-send-o whi"></i>
  </div>
</div>

    <div class="footer">

        <h4>Copyright © 2020 - 2030 <a href="/" class="plo200">RudraStories.in</a>. All Rights Reserved. | Designed by <a href="#" class="plo200"> Sutex Team</a>.

        </h4>
        <h4>
          <a href="/PrivacyPolicy" target="_blank" class="plo200">Privacy Policy</a>
          <a href="/TermsAndCond" target="_blank"  class="plo200">Terms & Conditions</a>
          <a href="/disclm" target="_blank" class="plo200">Disclaimer</a>
        </h4>
    </div>

</div> <!-- Closing .main-content-wrapper -->

</body>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script src="{{asset('js/lk.js')}}"></script>
<script src="{{asset('js/chpck45usr.js')}}"></script>
<script src="{{asset('js/sbs.js')}}"></script>
<script src="{{asset('js/hlp451sn.js')}}"></script>

  <script>
    // AOS.init();
    AOS.init();

AOS.init({

  // Settings that can be overridden on per-element basis, by `data-aos-*` attributes:
  offset: 120, // offset (in px) from the original trigger point
  delay: 20, // values from 0 to 3000, with step 50ms
  duration: 1500, // values from 0 to 3000, with step 50ms
  easing: 'ease', // default easing for AOS animations
  once: false, // whether animation should happen only once - while scrolling down
  mirror: false, // whether elements should animate out while scrolling past them
  anchorPlacement: 'top-bottom', // defines which position of the element regarding to window should trigger the animation

});

  </script>

</html>
