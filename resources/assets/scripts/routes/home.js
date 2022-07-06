export default {
  init() {

      document.querySelectorAll('.launch_video').forEach( trigger =>{
        trigger.addEventListener('click', e =>{
          e.preventDefault();
          document.getElementById("cover").click();
        })
      })

    const tag = document.createElement('script');

    tag.src = "https://www.youtube.com/iframe_api";
    const firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    tag.onload = function () {
      YT.ready(function() {
        let player;
        player = new window.YT.Player('player', {
          height: '720',
          width: '960',
          videoId: 'qNN-5OLooTY',
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
          }
        });

        // 4. The API will call this function when the video player is ready.
        function onPlayerReady(event) {
          if (document.getElementById("itemsWrapper")) {
            let vid = document.getElementById("ytb-vid");
            let open = document.getElementById("cover");
            let close = document.getElementById("close");
            let overlay = document.getElementById("over");

            open.addEventListener("click", () => {
              if (overlay.style.width == "0%" || overlay.style.width === '') {
                overlay.style.width = "100%";
                close.style.opacity = "1";
                close.style.visibility = "visible";
                player.playVideo()
              } else {
                overlay.style.width = "0%";
                close.style.opacity = "0";
                close.style.visibility = "hidden";
                player.stopVideo()
              }
            });

            close.addEventListener("click", () => {
              if (overlay.style.width == "100%") {
                overlay.style.width = "0%";
                close.style.opacity = "0";
                player.stopVideo()
              }
            });
          }

        }

        function onPlayerStateChange(event) {

        }
      });

    };


  }
};
