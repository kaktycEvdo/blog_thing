<div class="inner">
    <div>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $_SERVER['HTTP_REFERER'] ?>">Facebook</a>
        <a href="https://x.com/intent/tweet?url=<?php echo $_SERVER['HTTP_REFERER'] ?>">Twitter (X)</a>
        <a href="vk.com/share.php?url=<?php echo $_SERVER['HTTP_REFERER'] ?>">VKontakte</a>
        <a id="copytoclipboard" href="#">Copy link</a>
    </div>
</div>
<script>
    let linkbtn = document.getElementById('copytoclipboard');
    function copyToClipboard(){
        // Get the text field
        var copyText = '<?php echo $_SERVER['HTTP_REFERER'] ?>';

        // Copy the text inside the text field
        navigator.clipboard.writeText(copyText);
    }
    linkbtn.addEventListener('click', (e) => {
        e.preventDefault();
        copyToClipboard();
    })
</script>