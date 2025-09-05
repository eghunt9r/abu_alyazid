export default function Home() {
  return (
    <div style={{ display: "flex", justifyContent: "center", alignItems: "center", height: "100vh", background: "#000" }}>
      <video id="video" controls autoPlay width="800" height="450" style={{ background: "#000" }}></video>

      <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
      <script
        dangerouslySetInnerHTML={{
          __html: `
          const video = document.getElementById('video');
          const videoSrc = "/api/proxy?url=http://top102856-4k.org:80/live/abderahimkangoo201/pk6o5g2s9o/1.m3u8";

          if (Hls.isSupported()) {
            const hls = new Hls();
            hls.loadSource(videoSrc);
            hls.attachMedia(video);
          } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            video.src = videoSrc;
          }
        `,
        }}
      />
    </div>
  );
}