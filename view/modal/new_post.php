<form method="POST" enctype="multipart/form-data">
    <div>
        <label for="type">Тип поста:
            <select name="type" id="type">
                <option value="1">Текстовый</option>
                <option value="2">Видео</option>
            </select>
        </label>
    </div>
    <div class="text_type">
        <label for="cover">
            <img id="pre_cover" alt="Вставить обложку">
            <input type="file" name="cover" id="cover" accept="image/*">
        </label>
        <label for="content">
            Текст поста:
        </label>
        <textarea name="content" id="content"></textarea>
    </div>
    <div class="video_type hidden">
        <label for="video">
            Вставить видео
            <video id="pre_video"></video>
            <input id="video" name="video" type="file" accept="video/*">
        </label>
        <label for="header">
            Заголовок поста
        </label>
        <textarea name="header" id="header"></textarea>
    </div>
    <div>
        <label for="tags">Теги</label>
        <input type="text" name="tags" id="tags">
        <label for="commentary"><input type="checkbox" name="commentary" id="commentary"> Можно ли оставлять комментарии?</label>
        <input type="submit" value="Создать пост">
    </div>
</form>
<script>
    var text_section = document.querySelector('.text_type');
    var video_section = document.querySelector('.video_type');
    let type = document.querySelector('#type');

    type.addEventListener('change', (e) => {
        if(e.target.value == 1){
            text_section.classList.remove('hidden');
            video_section.classList.add('hidden');
        }
        else{
            video_section.classList.remove('hidden');
            text_section.classList.add('hidden');
        }
    });
    document.querySelector('#cover').addEventListener('change', (e) => {
        let output = document.getElementById("pre_cover");
        output.src = URL.createObjectURL(e.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src); // free memory
        }
    });
    document.querySelector('#video').addEventListener('change', (e) => {
        let output = document.getElementById("pre_video");
        output.src = URL.createObjectURL(e.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src); // free memory
        }
    });
</script>