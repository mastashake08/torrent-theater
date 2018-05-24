# Torrent Theatre

## Introduction

> Take part in the redecentralization of the web with movie streaming with the power of Bittorrent. Powered by the <a href="https://webtorrent.io">WebTorrent</a> javascript library, Torrent Theatre is a Laravel and Vue.js web application that allows users to stream and download movies over bittorrent protocol. No installations required and works across all major platforms. Please do not break the law when using this software and only watch open license and/or uncopyrighted material. You can find a working version of this application at <a href="https://torrent-theatre.online">https://torrent-theatre.online</a>
<br>
Donations to this project can be made in NEO, Ca$h app, Parker POS or CODE:
<br>
<br>
NEO: AH5cyWuuueBTAizYZtM4KJf9RSUs2uP3Ws
<br>
Ca$h: https://cash.me/$mastashake08
<br>
Parker POS: https://parkerpos.site/$jyrone_parker
<br>
Code: Send a pull request.

## Code Samples

Simple, elegant and easy to modify.
```
  created(){
          if (WebTorrent.WEBRTC_SUPPORT) {
          // WebRTC is supported
          this.client = new WebTorrent();
          this.fetchTorrents('https://yts.am/api/v2/list_movies.json?sort=seeds');
        } else {
          // Use a fallback
          alert("WebTorrent is not supported in this browser! Please upgrade your browser!");
                        }

                }
```

## Installation


> * compoer instal
> * php artisan migrate
> * npm install
> * npm run dev
