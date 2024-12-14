<?php
   try
   {
           $dbh = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $username, $password);
   } catch(PDOException $e)
   {
           http_response_code(401);
           trigger_error($e->getMessage());
           die("Database error!");
   }
   
   if (empty($_SESSION["account_id"])) {
           header("location: login.php");
   }
   
   try
   {
           $sth = $dbh->prepare("SELECT * FROM ".$usertablename." WHERE id = :id");
           $sth->execute([':id' => $_SESSION["account_id"] ]);
           $row = $sth->fetch();
   
   } catch(PDOException $e)
   {
           http_response_code(401);
           trigger_error($e->getMessage());
           die("Database error!");
   } 
   
   
   ?>
<div class="page-content  settings_page">
   <div class="row">
      <div class="col-lg-5 left_section">
         <div class="left_section-container">
            <div class="live-preview-section">
               <div class="lp_title-box">
                  <p class="lp_title">Push to server & Encoder details</p>
               </div>
            </div>
            <div class="ls_server-data">
               <div class="ls_server-data-top">
                  <div class="ls_information-container">
                     <div class="uppercase_title-sm">
                        <p>Server URL</p>
                     </div>
                     <div class="ls_information-input-container">
                        <input type="text" class="input_code" value="<?php echo "rtmp://publish.maghost.ro/transcoder?key=".$row["apphash"];?>" id="myInput1" disabled>
                        <button onclick="copyCodeFunc()" class="btn_input"><span class="nav_icon icon-file-copy"></span></button>
                     </div>
                  </div>
                  <div class="ls_information-container">
                     <div class="uppercase_title-sm">
                        <p>Stream key</p>
                     </div>
                     <div class="ls_information-input-container">
                        <input type="text" class="input_code" value="<?php echo $_SESSION["appname"]."?key=".$row["idhash"];?>" id="myInput2" disabled>
                        <button onclick="copyCodeFunc2()" class="btn_input"><span class="nav_icon icon-file-copy"></span></button>
                     </div>
                  </div>
               </div>
               <div class="ls_check-btn-container">
                  <label class="toggle" for="record">
                     <input type="checkbox" class="toggle__input" id="record" name="record" value="1" <?php if($row['record'] == "1") echo "checked"; ?>/>
                     <span class="toggle-track">
                        <span class="toggle-indicator">
                           <!-- 	This check mark is optional	 -->
                           <span class="checkMark">
                              <svg viewBox="0 0 24 24" id="ghq-svg-check" role="presentation" aria-hidden="true">
                                 <path d="M9.86 18a1 1 0 01-.73-.32l-4.86-5.17a1.001 1.001 0 011.46-1.37l4.12 4.39 8.41-9.2a1 1 0 111.48 1.34l-9.14 10a1 1 0 01-.73.33h-.01z"></path>
                              </svg>
                           </span>
                        </span>
                     </span>
                     <p class="ls_check-btn-text">Record your stream automatically</p>
                  </label>
               </div>
               <div class="ls_stream_targets">
                  <div class="lp_title-box">
                     <p class="lp_title">Stream targets activation</p>
                  </div>
                  <div class="stream-targets-description">
                     <p>Activate any of the following targets and do the needed setup on the right side of the screen, which will appear once you activate your Targets. </p>
                  </div>
                  <div class="ls_stream-targets-container">
                     <div class="ls_stream-targets-list row">
                        <div class="ls_stream-target col-lg-4">
                           <label class="stream_target">
                              <input type="checkbox" name="" id="fb_check" <?php if($row['fb_enable'] == "1") echo "checked"; ?>>
                              <div class="stream_target-box">
                                 <p>Stream to</p>
                                 <img src="assets/images/stream-target-facebook.png">
                              </div>
                           </label>
                        </div>
                        <div class="ls_stream-target col-lg-4">
                           <label class="stream_target">
                              <input type="checkbox" name="" id="yt_check" <?php if($row['yt_enable'] == "1") echo "checked"; ?>>
                              <div class="stream_target-box">
                                 <p>Stream to</p>
                                 <img src="assets/images/stream_target-yt.png">
                              </div>
                           </label>
                        </div>
                        <div class="ls_stream-target  col-lg-4">
                           <label class="stream_target">
                              <input type="checkbox" name="" id="hls_check" <?php if($row['hls_enable'] == "1") echo "checked"; ?>>
                              <div class="stream_target-box">
                                 <p>Stream to your</p>
                                 <p class="ls_stream-target-website">website</p>
                              </div>
                           </label>
                        </div>
                     </div>
                     <div class="ls_stream-targets-list row">
                        <div class="ls_stream-target  col-lg-4">
                           <label class="stream_target">
                              <input type="checkbox" name="" id="ig_check" <?php if($row['ig_enable'] == "1") echo "checked"; ?>>
                              <div class="stream_target-box">
                                 <p>Stream to</p>
                                 <img src="assets/images/Instagram_logo.png">
                              </div>
                           </label>
                        </div>
                     </div>
                     <!-- <div class="ls_stream-targets-list row">
                        <div class="ls_stream-target available_soon col-lg-4">
                        	<label class="stream_target">
                        		<input type="checkbox" name="">
                        		<div class="stream_target-box">	
                        			<p>Stream to</p>
                        			<img src="assets/images/Instagram_logo.png">
                        		</div>
                        	</label>
                        	<span>*available soon</span>
                        </div>
                        </div> -->
                  </div>
                  <div class="information_text-container">
                     <p class="information_text"><span class="nav_icon icon-info"></span>Any disable you will make in the future, will not erase your data.</p>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-lg-6 right_section">
         <div class="rs_container">
            <div class="rs_title-main">
               <p>Stream Target settings</p>
            </div>
            <div class="rs-stream-targets-list">
               <div  class="rs_stream-target fb_content-stream-target <?php if($row['fb_enable'] == "1") echo "active"; ?>">
                  <div class="stream_target-top">
                     <div class="stt_info">
                        <div class="stt-title stt-title-wimg">
                           <p>Stream to <img class="img_stream-to" src="assets/images/stream-target-facebook.png"></p>
                        </div>
                        <p class="stt-info-details">Connect your Facebook page by logging in using the button bellow. Once connected, approve all the required rights are you are ready to stream.</p>
                     </div>
                     <div class="stt_btn">
                        <div class="custom-selects custom-select-fb Offline">
                           <select id="fbstreamStatus" class="streamselect">
                              <option value="1" class="OnBit">OnBit</option>
                              <option value="1" class="OnBit" <?php if($row['fb_pid']) echo ' selected'; ?>>OnBit</option>
                              <option value="0" class="Offline" <?php if(!$row['fb_pid']) echo ' selected'; ?>>Offline</option>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="stream_target-btm">
                     <div class="tabs_section">
                        <!-- Tabs -->
                        <section id="tabs">
                           <div class="container_tabs rs_tabs">
                              <div class="row-tabs" style="width:100%">
                                 <div class="" style="width:100%">
                                    <nav class="mynav">
                                       <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                          <a class="nav-item nav-link <?php if($row['fb_api'] == 1) echo 'active'; ?>" id="nav-linked-tab" data-toggle="tab" href="#nav-linked" role="tab" aria-controls="nav-linked" <?php if($row['fb_manual_key']) { echo 'aria-selected="false"'; } else { echo 'aria-selected="true"'; } ?>>Linked. Automatic</a>
                                          <a class="nav-item nav-link <?php if($row['fb_api'] == 0) echo 'active'; ?>" id="nav-manual-tab" data-toggle="tab" href="#nav-manual" role="tab" aria-controls="nav-manual" <?php if($row['fb_manual_key']) echo 'aria-selected="true"'; ?>>Manual. Human actions</a>
                                       </div>
                                    </nav>
                                    <span class="target"></span>
                                    <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                                       <div class="tab-pane fade <?php if($row['fb_api'] == 1) echo 'active show'; ?>" id="nav-linked" role="tabpanel" aria-labelledby="nav-linked-tab">
                                          <div class="live_stats-list">
                                             <div class="uppercase_title-sm">
                                                <?php if($row['fb_page_token']) { ?>
                                                <p>Connected with page</p>
                                                <?php } ?>
                                             </div>
                                             <div class="tabs_section-content">
                                                <?php if($row['fb_page_token'] == "") { ?>
                                                <div id="link_list" style="display:none;">
                                                   <label for="page" style="color:white;"><b>Select Facebook Page:</b></label><br>
                                                   <select name="link_page" id="link_page">
                                                      <option></option>
                                                   </select>
                                                   <a href="javascript:link();" style="padding-left: 15px;">Link selected page</a>
                                                   <br><br><br>
                                                </div>
                                                <div id="login_btn" style="color:white;" class="social_login-btn-container">
                                                   <fb:login-button 
                                                      scope="public_profile,email,pages_read_engagement,pages_manage_posts"
                                                      onlogin="checkLoginState();">
                                                   </fb:login-button>
                                                </div>
                                                <div id="logout_btn" style="display:none;">
                                                   <a href="javascript:logout();">Log Out</a>
                                                </div>
                                                <?php } else { ?>
                                                <div class="rs__fb-page-info-section">
                                                   <div class="rs__fb-page-info-container">
                                                      <a>
                                                         <div class="rs_fb-page-avatar">
                                                            <!-- <img src="assets/images/elim-logi.png"> -->
                                                            <p class="rs_fb-page-name"><?php echo $row['fb_page_linked'];?></p>
                                                         </div>
                                                         <button class="rs_page-logout-action" onclick="javascript:unlink();"><span class="icon-cloud-error nav_icon"></span></button>
                                                      </a>
                                                   </div>
                                                </div>
                                                <?php } ?>
                                             </div>
                                             <div class="rs__social-details row">
                                                <div class="rs__social-details-title col-lg-6">
                                                   <div class="uppercase_title-sm">
                                                      <p>Title for facebook</p>
                                                   </div>
                                                   <div class="information-input-container">
                                                      <input type="text" class="input_code" id="fb_title" name="fb_title" value="<?php echo $row['fb_title'] ? $row['fb_title']  : '<page-title>';?>" >
                                                   </div>
                                                </div>
                                                <div class="rs__social-details-title col-lg-6">
                                                   <div class="uppercase_title-sm">
                                                      <p>Description for facebook</p>
                                                   </div>
                                                   <div class="information-input-container">
                                                      <input type="text" class="input_code" id="fb_description" name="fb_description" value="<?php echo $row['fb_descr'] ? $row['fb_descr']  : '<page-description>';?>">
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="tab-pane fade <?php if($row['fb_api'] == 0) echo 'active show'; ?>" id="nav-manual" role="tabpanel" aria-labelledby="nav-manual-tab">
                                          <div class="live_stats-list manual_facebook-tab">
                                             <div class="stt_info">
                                                <p class="stt-info-details">In the event that problems appear with automatic application, you can manually connect video feed to your <a href="https://www.facebook.com/live/producer/" target="_blank">Facebook account.</a>
                                                </p>
                                             </div>
                                             <div class="rs__social-details row">
                                                <div class="rs__social-details-title col-lg-8">
                                                   <div class="uppercase_title-sm">
                                                      <p>Server Url</p>
                                                   </div>
                                                   <div class="information-input-container">
                                                      <input type="text" class="input_code" value="rtmps://live-api-s.facebook.com:443/rtmp/" disabled >
                                                   </div>
                                                </div>
                                                <div class="rs__social-details-title col-lg-8">
                                                   <div class="uppercase_title-sm">
                                                      <p>Stream Key</p>
                                                      <div class="tooltip"><span class="nav_icon icon-question"></span><span class="tooltiptext">Use persistent stream key, so you don't have to change it every time a stream is scheduled.</span></div>
                                                   </div>
                                                   <div class="information-input-container">
                                                      <input type="text" class="input_code" placeholder="stream key" id="fb_manual_key" name="fb_manual_key" value="<?php echo $row['fb_manual_key'];?>">
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="ls_check-btn-container">
                                       <label class="toggle" for="fb_auto_start">
                                          <input type="checkbox" class="toggle__input" id="fb_auto_start" name="fb_auto_start" value="1" <?php if($row['fb_auto_start'] == "1") echo "checked"; ?> />
                                          <span class="toggle-track">
                                             <span class="toggle-indicator">
                                                <!-- 	This check mark is optional	 -->
                                                <span class="checkMark">
                                                   <svg viewBox="0 0 24 24" id="ghq-svg-check" role="presentation" aria-hidden="true">
                                                      <path d="M9.86 18a1 1 0 01-.73-.32l-4.86-5.17a1.001 1.001 0 011.46-1.37l4.12 4.39 8.41-9.2a1 1 0 111.48 1.34l-9.14 10a1 1 0 01-.73.33h-.01z"></path>
                                                   </svg>
                                                </span>
                                             </span>
                                          </span>
                                          <p class="ls_check-btn-text">Publish instantly once stream has started</p>
                                       </label>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </section>
                        <!-- ./Tabs -->
                     </div>
                  </div>
               </div>
               <div class="rs-stream-targets-list yt_content-stream-target <?php if($row['yt_enable'] == "1") echo "active"; ?>">
                  <div  class="rs_stream-target">
                     <div class="stream_target-top">
                        <div class="stt_info">
                           <div class="stt-title stt-title-wimg">
                              <p>Stream to <img src="assets/images/stream_target-yt2.png" class="img_stream-to"></p>
                           </div>
                           <p class="stt-info-details">Fill all the requested information based on you YouTube channel. Pay attention when you copy and paste to have all thee information precisely without any space.</p>
                        </div>
                        <div class="stt_btn">
                           <div class="custom-selects custom-select-yt Offline">
                              <select id="ytstreamStatus" class="streamselect">
                                 <option value="1" class="OnBit">OnBit</option>
                                 <option value="1" class="OnBit" <?php if($row['yt_pid']) echo ' selected'; ?>>OnBit</option>
                                 <option value="0" class="Offline" <?php if(!$row['yt_pid']) echo ' selected'; ?>>Offline</option>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="stream_target-btm">
                        <form id="yt_key_form" action="" method="post">
                           <div class="rs__social-details row">
                              <div class="rs__social-details-title col-lg-6">
                                 <div class="uppercase_title-sm">
                                    <p>Stream key</p>
                                 </div>
                                 <div class="information-input-container">
                                    <input type="text" class="input_code" id="yt_key" name="yt_key" placeholder="YouTube key" value="<?php echo $row['yt_key'];?>" >
                                 </div>
                              </div>
                              <div class="rs__social-details-title col-lg-6">
                                 <div class="uppercase_title-sm">
                                    <p>Default stream url</p>
                                 </div>
                                 <div class="information-input-container">
                                    <input type="text" class="input_code" id="yt_url" name="yt_url" value="rtmp://a.rtmp.youtube.com/live2" readonly>
                                 </div>
                              </div>
                           </div>
                           <div class="rs__social-details row">
                              <div class="rs__social-details-title col-lg-12">
                                 <div class="uppercase_title-sm">
                                    <p>Channel ID</p>
                                 </div>
                                 <div class="information-input-container">
                                    <input type="text" class="input_code" id="yt_ch_id" name="yt_ch_id" placeholder="YouTube Channel ID" value="<?php echo $row['yt_ch_id'];?>" >
                                 </div>
                              </div>
                           </div>
                           <input type="hidden" name="update_yt_key" value="1">
                        </form>
                        <div class="ls_check-btn-container">
                           <label class="toggle" for="yt_auto_start">
                              <input type="checkbox" class="toggle__input" id="yt_auto_start" name="yt_auto_start" value="1" <?php if($row['yt_auto_start'] == "1") echo "checked"; ?> />
                              <span class="toggle-track">
                                 <span class="toggle-indicator">
                                    <!-- 	This check mark is optional	 -->
                                    <span class="checkMark">
                                       <svg viewBox="0 0 24 24" id="ghq-svg-check" role="presentation" aria-hidden="true">
                                          <path d="M9.86 18a1 1 0 01-.73-.32l-4.86-5.17a1.001 1.001 0 011.46-1.37l4.12 4.39 8.41-9.2a1 1 0 111.48 1.34l-9.14 10a1 1 0 01-.73.33h-.01z"></path>
                                       </svg>
                                    </span>
                                 </span>
                              </span>
                              <p class="ls_check-btn-text">Publish instantly once stream has started</p>
                           </label>
                        </div>
                     </div>
                  </div>
               </div>
			   <div class="rs-stream-targets-list ig_content-stream-target <?php if($row['ig_enable'] == "1") echo "active"; ?>">
                  <div  class="rs_stream-target">
                     <div class="stream_target-top">
                        <div class="stt_info">
                           <div class="stt-title stt-title-wimg">
                              <p>Stream to <img class="img_stream-to" src="assets/images/Instagram_logo-white.png"></p>
                           </div>
                           <p class="stt-info-details">Fill all the requested information based on you Instagram channel. Pay attention when you copy and paste to have all thee information precisely without any space.</p>
                        </div>
                        <div class="stt_btn">
                           <div class="custom-selects custom-select-ig Offline">
                              <select id="igstreamStatus" class="streamselect">
                                 <option value="1" class="OnBit">OnBit</option>
                                 <option value="1" class="OnBit" <?php if($row['ig_pid']) echo ' selected'; ?>>OnBit</option>
                                 <option value="0" class="Offline" <?php if(!$row['ig_pid']) echo ' selected'; ?>>Offline</option>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="stream_target-btm">
                        <form id="ig_key_form" action="" method="post">
                           <div class="rs__social-details row">
                              <div class="rs__social-details-title col-lg-9">
                                 <div class="uppercase_title-sm">
                                    <p>Server URL</p>
                                 </div>
                                 <div class="information-input-container">
                                    <input type="text" class="input_code" id="ig_url" name="ig_url" placeholder="Instagram URL" value="rtmps://live-upload.instagram.com:443/rtmp" disabled>
                                 </div>
                              </div>
                           </div>
                           <div class="rs__social-details row">
                              <div class="rs__social-details-title col-lg-12">
                                 <div class="uppercase_title-sm">
                                    <p>Stream Key</p>
                                 </div>
                                 <div class="information-input-container">
                                    <input type="text" class="input_code" id="ig_key" name="ig_key" placeholder="Instagram key" value="<?php echo $row['ig_key'];?>" >
                                 </div>
                              </div>
                           </div>
                           <input type="hidden" name="update_ig_key" value="1">
                        </form>
                     </div>
                  </div>
               </div>
              <div class="rs-stream-targets-list hls_content-stream-target <?php if($row['hls_enable'] == "1") echo "active"; ?>">
                  <div  class="rs_stream-target">
                     <div class="stream_target-top">
                        <div class="stt_info">
                           <div class="stt-title stt-title-wimg">
                              <p>Stream to website</p>
                           </div>
                           <p class="stt-info-details">Stream to website and embeded players with HLS support.</p>
                        </div>
                        <div class="stt_btn">
                           <div class="custom-selects custom-select-hls Offline">
                              <select id="hlsstreamStatus" class="streamselect">
                                 <option value="1" class="OnBit">OnBit</option>
                                 <option value="1" class="OnBit" <?php if($row['hls_pid']) echo ' selected'; ?>>OnBit</option>
                                 <option value="0" class="Offline" <?php if(!$row['hls_pid']) echo ' selected'; ?>>Offline</option>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="stream_target-btm">
                        <div class="ls_stream-data-details">
                           <div class="information-container">
                              <div class="uppercase_title-sm">
                                 <p>Title for live feed</p>
                              </div>
                              <div class="information-input-container">
                                 <input type="text" class="input_code" value="<?php echo $row['hls_title'] ? $row['hls_title']  : '<page-title>';?>" id="hls_title" name="hls_title">
                              </div>
                           </div>
                           <div class="information-container">
                              <div class="uppercase_title-sm">
                                 <p>Description for Live feed</p>
                              </div>
                              <div class="information-input-container">
                                 <input type="text" class="input_code" value="<?php echo $row['hls_description'] ? $row['hls_description']  : '<page-description>';?>" id="hls_description" name="hls_description">
                              </div>
                           </div>
                        </div>
                        <div class="ls_check-btn-container">
                           <label class="toggle" for="hls_auto_start">
                              <input type="checkbox" class="toggle__input" id="hls_auto_start" name="hls_auto_start" value="1" <?php if($row['hls_auto_start'] == "1") echo "checked"; ?> />
                              <span class="toggle-track">
                                 <span class="toggle-indicator">
                                    <!-- 	This check mark is optional	 -->
                                    <span class="checkMark">
                                       <svg viewBox="0 0 24 24" id="ghq-svg-check" role="presentation" aria-hidden="true">
                                          <path d="M9.86 18a1 1 0 01-.73-.32l-4.86-5.17a1.001 1.001 0 011.46-1.37l4.12 4.39 8.41-9.2a1 1 0 111.48 1.34l-9.14 10a1 1 0 01-.73.33h-.01z"></path>
                                       </svg>
                                    </span>
                                 </span>
                              </span>
                              <p class="ls_check-btn-text">Publish instantly once stream has started</p>
                           </label>
                        </div>
                     </div>
                  </div>
               </div>
               
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<form id="facebook_form" action="" method="post">
   <input type="hidden" id="fb_page_id" name="fb_page_id" value="">
   <input type="hidden" id="fb_page_linked" name="fb_page_linked" value="">
   <input type="hidden" id="fb_page_token" name="fb_page_token" value="">
   <input type="hidden" id="fb_page_unlink" name="fb_page_unlink" value="">
   <input type="hidden" id="fb_logout" name="fb_logout" value="">
</form>
<!--
   <form id="hls_form" action="" method="post">
   	<input type="hidden" id="hls_start_stop" name="hls_start_stop" value=""/>
   </form>
   -->
<br><br><br><Br><br><br>
<script>
   var myInterval;
   var yt_pid = 0;
   var fb_pid = 0;
   var ig_pid = 0;
   var hls_pid = 0;
   <?php if($row['yt_pid']) echo 'yt_pid = 1;'; ?>
   <?php if($row['fb_pid']) echo 'fb_pid = 1;'; ?>
   <?php if($row['ig_pid']) echo 'ig_pid = 1;'; ?>
   <?php if($row['hls_pid']) echo 'hls_pid = 1;'; ?>
   muser = "<?php echo $_SESSION["appname"];?>";
   
   const interval = setInterval(function() {
      getStats();
   }, 1000);
   
   function getStats(){
   	$.ajax({
   	   type: "GET",
   	   url: "/api.php",
   	   data: "action=xml&u=" +muser,
   	   beforeSend: function(){  },
   	   success: function(msg){
   			res = JSON.parse(msg);
   			if(res.streaming == "1") {
   				if(res.yt_pid > 0) {
   					yt_pid = 1;
   					$(".custom-select-yt").removeClass().addClass('custom-selects custom-select-yt OnBit');
   					$(".ytstreamStatus").html('OnBit');
   					$("#ytstreamStatus").val('1');
   				} else {
   					yt_pid = 0;
   					$(".custom-select-yt").removeClass().addClass('custom-selects custom-select-yt Offline');
   					$(".ytstreamStatus").html('Offline');
   					$("#ytstreamStatus").val('0');
   				}
   				if(res.fb_pid > 0) {
   					fb_pid = 1;
   					$(".custom-select-fb").removeClass().addClass('custom-selects custom-select-fb OnBit');
   					$(".fbstreamStatus").html('OnBit');
   					$("#fbstreamStatus").val('0');
   				} else {
   					fb_pid = 0;
   					$(".custom-select-fb").removeClass().addClass('custom-selects custom-select-fb Offline');
   					$(".fbstreamStatus").html('Offline');
   					$("#fbstreamStatus").val('0');
   				}
				if(res.ig_pid > 0) {
   					ig_pid = 1;
   					$(".custom-select-ig").removeClass().addClass('custom-selects custom-select-ig OnBit');
   					$(".igstreamStatus").html('OnBit');
   					$("#igstreamStatus").val('1');
   				} else {
   					ig_pid = 0;
   					$(".custom-select-ig").removeClass().addClass('custom-selects custom-select-ig Offline');
   					$(".igstreamStatus").html('Offline');
   					$("#igstreamStatus").val('0');
   				}
   				if(res.hls_pid > 0) {
   					hls_pid = 1;
   					$(".custom-select-hls").removeClass().addClass('custom-selects custom-select-hls OnBit');
   					$(".hlsstreamStatus").html('OnBit');
   					$("#hlsstreamStatus").val('0');
   				} else {
   					hls_pid = 0;
   					$(".custom-select-hls").removeClass().addClass('custom-selects custom-select-hls Offline');
   					$(".hlsstreamStatus").html('Offline');
   					$("#hlsstreamStatus").val('0');
   				}
   			}
   			if(res.streaming == "0") {		
   				fb_pid = 0;
   				yt_pid = 0;
				ig_pid = 0;
   				$(".custom-select-yt").removeClass().addClass('custom-selects custom-select-yt Offline');
   				$(".ytstreamStatus").html('Offline');
   				$("#ytstreamStatus").val('0');
   				$(".custom-select-fb").removeClass().addClass('custom-selects custom-select-fb Offline');
   				$(".fbstreamStatus").html('Offline');
   				$("#fbstreamStatus").val('0');
				$(".custom-select-ig").removeClass().addClass('custom-selects custom-select-ig Offline');
   				$(".igstreamStatus").html('Offline');
   				$("#igstreamStatus").val('0');
   				$(".custom-select-hls").removeClass().addClass('custom-selects custom-select-hls Offline');				
   				$(".hlsstreamStatus").html('Offline');
   				$("#hlsstreamStatus").val('0');
   			}
   	   }
   	});
   }
   
   var x, i, j, l, ll, selElmnt, a, b, c;
   /*look for any elements with the class "custom-select":*/
   x = document.getElementsByClassName("custom-selects");
   l = x.length;
   for (i = 0; i < l; i++) {
     selElmnt = x[i].getElementsByTagName("select")[0];
     ll = selElmnt.length;
     /*for each element, create a new DIV that will act as the selected item:*/
     a = document.createElement("DIV");
     a.setAttribute("class", "select-selected " + selElmnt.id );
     a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
     x[i].appendChild(a);
   
     /*for each element, create a new DIV that will contain the option list:*/
     b = document.createElement("DIV");
     b.setAttribute("class", "select-items select-hide");
     for (j = 1; j < ll; j++) {
       /*for each option in the original select element,
       create a new DIV that will act as an option item:*/
       c = document.createElement("DIV");
       c.innerHTML = selElmnt.options[j].innerHTML;
       c.addEventListener("click", function(e) {
           /*when an item is clicked, update the original select box,
           and the selected item:*/
           var y, i, k, s, h, sl, yl;
           s = this.parentNode.parentNode.getElementsByTagName("select")[0];
           sl = s.length;
           h = this.parentNode.previousSibling;
           for (i = 0; i < sl; i++) {
             if (s.options[i].innerHTML == this.innerHTML) {
               s.selectedIndex = i;
               h.innerHTML = this.innerHTML;
               y = this.parentNode.getElementsByClassName("same-as-selected");
               yl = y.length;
               for (k = 0; k < yl; k++) {
                 y[k].removeAttribute("class");
               }
               this.setAttribute("class", "same-as-selected");
               break;
             }
           }
           h.click();
   		s.dispatchEvent(new Event('change'));
       });
       b.appendChild(c);
     }
     x[i].appendChild(b);
     a.addEventListener("click", function(e) {
         /*when the select box is clicked, close any other select boxes,
         and open/close the current select box:*/
         e.stopPropagation();
         closeAllSelect(this);
         this.nextSibling.classList.toggle("select-hide");
         this.classList.toggle("select-arrow-active");
       });
   }
   
   
   function closeAllSelect(elmnt) {
     /*a function that will close all select boxes in the document,
     except the current select box:*/
     var x, y, i, xl, yl, arrNo = [];
     x = document.getElementsByClassName("select-items");
     y = document.getElementsByClassName("select-selected");
     xl = x.length;
     yl = y.length;
     for (i = 0; i < yl; i++) {
       if (elmnt == y[i]) {
         arrNo.push(i)
       } else {
         y[i].classList.remove("select-arrow-active");
       }
     }
     for (i = 0; i < xl; i++) {
       if (arrNo.indexOf(i)) {
         x[i].classList.add("select-hide");
       }
     }
   }
   /*if the user clicks anywhere outside the select box,
   then close all select boxes:*/
   document.addEventListener("click", closeAllSelect);
   
   
   
   $(document).ready(function(){
     $('#ytstreamStatus').change(function() {
       v = $(this).val();
   	console.log(v);
   	console.log(yt_pid);
   	if(v != yt_pid) {
   		if(v == 1) {
   			$.ajax({
   			   type: "POST",
   			   url: "/index.php",
   			   data: "yt_start_stop=start",
   			   beforeSend: function(){  },
   			   success: function(msg){
   			   }
   			});
   		} else {
   			$.ajax({
   			   type: "POST",
   			   url: "/index.php",
   			   data: "yt_start_stop=stop",
   			   beforeSend: function(){  },
   			   success: function(msg){
   			   }
   			});
   		}
   	}
     });
     $('#fbstreamStatus').change(function() {
       v = $(this).val();
   	if(v != fb_pid) {
   		if(v == 1) {
   			$.ajax({
   			   type: "POST",
   			   url: "/index.php",
   			   data: "fb_start_stop=start",
   			   beforeSend: function(){  },
   			   success: function(msg){
   			   }
   			});
   		} else {
   			$.ajax({
   			   type: "POST",
   			   url: "/index.php",
   			   data: "fb_start_stop=stop",
   			   beforeSend: function(){  },
   			   success: function(msg){
   			   }
   			});
   		}
   	}
     });
	 
	      $('#igstreamStatus').change(function() {
			v = $(this).val();
			if(v == 1) {
				$.ajax({
				   type: "POST",
				   url: "/index.php",
				   data: "ig_start_stop=start",
				   beforeSend: function(){  },
				   success: function(msg){
				   }
				});
			} else {
				$.ajax({
				   type: "POST",
				   url: "/index.php",
				   data: "ig_start_stop=stop",
				   beforeSend: function(){  },
				   success: function(msg){
				   }
				});
			}
		
		});
     
     $('#hlsstreamStatus').change(function() {
       v = $(this).val();
   	if(v != hls_pid) {
   		if(v == 1) {
   			$.ajax({
   			   type: "POST",
   			   url: "/index.php",
   			   data: "hls_start_stop=start",
   			   beforeSend: function(){  },
   			   success: function(msg){
   			   }
   			});
   		} else {
   			$.ajax({
   			   type: "POST",
   			   url: "/index.php",
   			   data: "hls_start_stop=stop",
   			   beforeSend: function(){  },
   			   success: function(msg){
   			   }
   			});
   		}
   	}
     });
     
   });
   
   var tokens = [];
   var acc_token = "";
   
   window.fbAsyncInit = function() {
   FB.init({
     appId      : '677514669868733',
     cookie     : true,
     xfbml      : true,
     version    : 'v20.0'
   });
     
   FB.getLoginStatus(function(response) {
   	statusChangeCallback(response);
   });
     
   };
     
   function statusChangeCallback(response){
   	if(response.status === 'connected' ) {
   		console.log('Logged in');
   		$('#login_btn').hide();
   		$('#logout_btn').show();
   		get_token(response.authResponse.accessToken, response.authResponse.userID);
   	} else {
   		console.log('Not logged in');
   		$('#login_btn').show();
   		$('#logout_btn').hide();
   	}
   };
     
   function checkLoginState() {
     FB.getLoginStatus(function(response) {
   	statusChangeCallback(response);
     });
   };
   
   function get_token(token, user) {
   	
   	$.ajax({
   	   type: "POST",
   	   url: "/api_get_token.php",
   	   data: "token="+token+"&user="+user,
   	   beforeSend: function(){  },
   	   success: function(msg){
   			res = JSON.parse(msg);
   			$("#link_page").html(res.html);
   			$("#link_list").show();
   	   }
   	});
   }
   
   
   	  
   function logout() {
   	FB.logout(function(response) {
   		$('#fb_logout').val(1);
   		$("#facebook_form").submit();
   	});
   };
   	
   function link(){
   	var id = $('#link_page').val();
   	var name = $( "#link_page option:selected" ).text();
   	var token = $( "#link_page option:selected" ).data('token');
   
   	$('#fb_page_id').val(id);
   	$('#fb_page_linked').val(name);
   	$('#fb_page_token').val(token);
   	
   	$("#facebook_form").submit();
   }
   
   function unlink(){
   	$('#fb_page_unlink').val(1);
   	$("#facebook_form").submit();
   }
   
   
   (function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
<script>
   function copyCodeFunc() {
    // Get the text field
    var copyText = document.getElementById("myInput1");
   
    // Select the text field
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices
   
     // Copy the text inside the text field
    navigator.clipboard.writeText(copyText.value);
   
    // Alert the copied text
    alert("Copied the text: " + copyText.value);
   }
   function copyCodeFunc2() {
    // Get the text field
    var copyText = document.getElementById("myInput2");
   
    // Select the text field
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices
   
     // Copy the text inside the text field
    navigator.clipboard.writeText(copyText.value);
   
    // Alert the copied text
    alert("Copied the text: " + copyText.value);
   }
</script>
<script>
   $('#fb_check').change(function(){
       if($(this).is(":checked")) {
           $('.fb_content-stream-target').addClass("active");
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "fb_enable=activ",
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
       } else {
           $('.fb_content-stream-target').removeClass("active");
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "fb_enable=inactiv",
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
       }
   });
   
   $('#yt_check').change(function(){
       if($(this).is(":checked")) {
           $('.yt_content-stream-target').addClass("active");
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "yt_enable=activ",
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
       } else {
           $('.yt_content-stream-target').removeClass("active");
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "yt_enable=inactiv",
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
       }
   });
   
   $('#ig_check').change(function(){
       if($(this).is(":checked")) {
           $('.ig_content-stream-target').addClass("active");
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "ig_enable=activ",
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
       } else {
           $('.ig_content-stream-target').removeClass("active");
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "ig_enable=inactiv",
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
       }
   });
   
   $('#hls_check').change(function(){
       if($(this).is(":checked")) {
           $('.hls_content-stream-target').addClass("active");
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "hls_enable=activ",
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
       } else {
           $('.hls_content-stream-target').removeClass("active");
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "hls_enable=inactiv",
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
       }
   });
   
   $('#fb_auto_start').change(function(){
       if($(this).is(":checked")) {
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "update_auto_fb=1&fb_auto_start=1",
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
       } else {
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "update_auto_fb=1&fb_auto_start=0",
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
       }
   });
   
   $('#yt_auto_start').change(function(){
       if($(this).is(":checked")) {
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "update_auto_yt=1&yt_auto_start=1",
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
       } else {
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "update_auto_yt=1&yt_auto_start=0",
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
       }
   });
   
   $('#hls_auto_start').change(function(){
       if($(this).is(":checked")) {
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "update_auto_hls=1&hls_auto_start=1",
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
       } else {
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "update_auto_hls=1&hls_auto_start=0",
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
       }
   });
   
   $('#record').change(function(){
       if($(this).is(":checked")) {
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "update_record=1&record=1",
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
       } else {
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "update_record=1&record=0",
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
       }
   });
   
   
   var doneTypingInterval = 1000;
   
   var hls_title_timer;         
   $('#hls_title').keyup(function(){
   	if (hls_title_timer !== null) {
               clearTimeout(hls_title_timer);
           }
   	hls_title_timer = setTimeout(function() {
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "update_hls_title=1&val="+$('#hls_title').val(),
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
   	}, doneTypingInterval);
   	
   });
   
   var hls_description_timer;                
   $('#hls_description').keyup(function(){
   	if (hls_description_timer !== null) {
               clearTimeout(hls_description_timer);
           }
   	hls_description_timer = setTimeout(function() {
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "update_hls_description=1&val="+$('#hls_description').val(),
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
   	}, doneTypingInterval);
   	
   });
   
   var fb_title_timer;  
   $('#fb_title').keyup(function(){
   	if (fb_title_timer !== null) {
               clearTimeout(fb_title_timer);
           }
   	fb_title_timer = setTimeout(function() {
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "update_fb_title=1&val="+$('#fb_title').val(),
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
   	}, doneTypingInterval);
   	
   });
   
   var fb_description_timer;                
   $('#fb_description').keyup(function(){
   	if (fb_description_timer !== null) {
               clearTimeout(fb_description_timer);
           }
   	fb_description_timer = setTimeout(function() {
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "update_fb_description=1&val="+$('#fb_description').val(),
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
   	}, doneTypingInterval);
   	
   });
   
   var yt_key_timer;                
   $('#yt_key').keyup(function(){
   	if (yt_key_timer !== null) {
               clearTimeout(yt_key_timer);
           }
   	yt_key_timer = setTimeout(function() {
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "update_yt_key=1&val="+$('#yt_key').val(),
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
   	}, doneTypingInterval);
   	
   });
   
   var yt_ch_id_timer;                
   $('#yt_ch_id').keyup(function(){
   	if (yt_ch_id_timer !== null) {
               clearTimeout(yt_ch_id_timer);
           }
   	yt_ch_id_timer = setTimeout(function() {
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "update_yt_ch_id=1&val="+$('#yt_ch_id').val(),
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
   	}, doneTypingInterval);
   	
   });
   
   /*
   var ig_url_timer;                
   $('#ig_url').keyup(function(){
   	if (ig_url_timer !== null) {
               clearTimeout(ig_url_timer);
           }
   	ig_url_timer = setTimeout(function() {
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "update_ig_url=1&val="+$('#ig_url').val(),
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
   	}, doneTypingInterval);
   	
   });
   */
   
   var ig_key_timer;                
   $('#ig_key').keyup(function(){
   	if (ig_key_timer !== null) {
               clearTimeout(ig_key_timer);
           }
   	ig_key_timer = setTimeout(function() {
		var key = btoa($('#ig_key').val());
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "update_ig_key=1&val="+key,
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
   	}, doneTypingInterval);
   });
   
   var fb_manual_key_timer;                
   $('#fb_manual_key').keyup(function(){
   	if (fb_manual_key_timer !== null) {
               clearTimeout(fb_manual_key_timer);
           }
   	fb_manual_key_timer = setTimeout(function() {
   		$.ajax({
   		   type: "POST",
   		   url: "/index.php",
   		   data: "update_fb_manual_key=1&val="+$('#fb_manual_key').val(),
   		   beforeSend: function(){  },
   		   success: function(msg){
   		   }
   		});
   	}, doneTypingInterval);
   	
   });
   
   
   $( "#nav-linked-tab" ).click(function() {
      $.ajax({
   	   type: "POST",
   	   url: "/index.php",
   	   data: "update_fb_api=1&val=1",
   	   beforeSend: function(){  },
   	   success: function(msg){
   	   }
   	});
   });
   
   $( "#nav-manual-tab" ).click(function() {
       $.ajax({
   	   type: "POST",
   	   url: "/index.php",
   	   data: "update_fb_api=1&val=0",
   	   beforeSend: function(){  },
   	   success: function(msg){
   	   }
   	});
   });
   
   
</script>