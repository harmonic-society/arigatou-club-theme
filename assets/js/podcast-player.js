/**
 * ポッドキャスト ミニオーディオプレーヤー
 */
document.addEventListener('DOMContentLoaded', function() {
    var currentAudio = null;
    var currentBtn = null;

    document.querySelectorAll('.podcast-play-btn').forEach(function(btn) {
        var player = btn.closest('.podcast-audio-player');
        var audio = player.querySelector('audio');
        var progressFill = player.querySelector('.podcast-progress-fill');
        var progressBar = player.querySelector('.podcast-progress-bar');
        var timeDisplay = player.querySelector('.podcast-time');

        btn.addEventListener('click', function() {
            // 別のオーディオが再生中なら停止
            if (currentAudio && currentAudio !== audio) {
                currentAudio.pause();
                currentAudio.currentTime = 0;
                currentBtn.classList.remove('playing');
                var prevPlayer = currentBtn.closest('.podcast-audio-player');
                prevPlayer.querySelector('.podcast-progress-fill').style.width = '0%';
                prevPlayer.querySelector('.podcast-time').textContent = '0:00';
            }

            if (audio.paused) {
                audio.play();
                btn.classList.add('playing');
                currentAudio = audio;
                currentBtn = btn;
            } else {
                audio.pause();
                btn.classList.remove('playing');
                currentAudio = null;
                currentBtn = null;
            }
        });

        audio.addEventListener('timeupdate', function() {
            if (audio.duration) {
                var pct = (audio.currentTime / audio.duration) * 100;
                progressFill.style.width = pct + '%';
                var mins = Math.floor(audio.currentTime / 60);
                var secs = Math.floor(audio.currentTime % 60);
                timeDisplay.textContent = mins + ':' + (secs < 10 ? '0' : '') + secs;
            }
        });

        audio.addEventListener('ended', function() {
            btn.classList.remove('playing');
            progressFill.style.width = '0%';
            timeDisplay.textContent = '0:00';
            currentAudio = null;
            currentBtn = null;
        });

        // プログレスバーでシーク
        progressBar.addEventListener('click', function(e) {
            if (audio.duration) {
                var rect = progressBar.getBoundingClientRect();
                var pct = (e.clientX - rect.left) / rect.width;
                audio.currentTime = pct * audio.duration;
            }
        });
    });
});
