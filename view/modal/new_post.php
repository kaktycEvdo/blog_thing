<form method="POST" enctype="multipart/form-data">
    <div>
        <label for="type">Тип поста:
            <select name="type" id="type">
                <option value="1">Текстовый</option>
                <option value="2">Видео</option>
                <option value="3">Сторис</option>
            </select>
        </label>
    </div>
    <div class="text_type">
        <label for="cover">
            <img id="pre_cover" alt="Вставить обложку">
            <input type="file" name="cover" id="cover" accept="image/*">
        </label>
        <!-- Позднее необходимо сделать форматирование текста -->
        <label for="content">
            Текст поста:
        </label>
        <textarea name="content" id="content"></textarea>
        <label for="tags_t">Теги</label>
        <input type="text" name="tags_t" id="tags_t">
    </div>
    <div class="video_type hidden">
        <label for="video">
            Вставить видео
            <video id="pre_video" src=""></video>
            <input id="video" name="video"  type="file" accept="video/*">
        </label>
        <label for="tags_v">Теги</label>
        <input type="text" name="tags_v" id="tags_v">
    </div>
    <div class="story_type hidden">
        <!-- позже доделать -->
        <label for="story">
            Вставить видео/изображение
            <video id="pre_video_story" src="" class="hidden"></video>
            <img id="pre_image_story" src="" class="hidden">
            <input id="story" name="story" type="file" accept="video/*, image/*">
        </label>
        <label for="description">
            Описание сторис
        </label>
        <textarea name="description" id="description"></textarea>
    </div>
    <div>
        <label for="header">
            Заголовок поста
        </label>
        <textarea name="header" id="header"></textarea>
        <label for="commentary"><input type="checkbox" checked name="commentary" id="commentary">Можно ли оставлять комментарии?</label>
        <input type="submit" value="Создать пост">
    </div>
</form>
<script>
    var text_section = document.querySelector('.text_type');
    var video_section = document.querySelector('.video_type');
    var story_section = document.querySelector('.story_type');
    let type = document.querySelector('#type');

    type.addEventListener('change', (e) => {
        if(e.target.value == 1){
            text_section.classList.remove('hidden');
            video_section.classList.add('hidden');
            story_section.classList.add('hidden');
        }
        else if (e.target.value == 2){
            video_section.classList.remove('hidden');
            text_section.classList.add('hidden');
            story_section.classList.add('hidden');
        }
        else if (e.target.value == 3){
            story_section.classList.remove('hidden');
            text_section.classList.add('hidden');
            video_section.classList.add('hidden');
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
        let input = document.querySelector('#video');
        output.src = URL.createObjectURL(e.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src); // free memory
        }
        document.querySelector('label[for="video"]').innerHTML = null;
        document.querySelector('label[for="video"]').appendChild(output);
        document.querySelector('label[for="video"]').appendChild(input);
    });
    document.querySelector('#story').addEventListener('change', (e) => {
        // позже доделать
        let input = document.querySelector('#story');
        if(e.target.files[0]['type'].match(/image\//)){
            let output = document.getElementById("pre_image_story");
            output.src = URL.createObjectURL(e.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src); // free memory
            }
            document.querySelector('label[for="story"]').innerHTML = null;
            document.querySelector('label[for="story"]').appendChild(output);
            document.querySelector('label[for="story"]').appendChild(input);
        }
        else if(e.target.files[0]['type'].match(/video\//)){
            let output = document.getElementById("pre_video_story");
            output.src = URL.createObjectURL(e.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src); // free memory
            }
            document.querySelector('label[for="story"]').innerHTML = null;
            document.querySelector('label[for="story"]').appendChild(output);
            document.querySelector('label[for="story"]').appendChild(input);
        }
    });
</script>