////////// switch sidebar


$(".update_pic").click(function() {
    $('#theFileInput').click();
})

const switch_side = (name) => {
    arr = ['profile', 'chat_users', 'user_enline', 'setting']
    for (i = 0; i < arr.length; i++) {
        if (name == arr[i]) {
            let sidebare = '.' + arr[i];
            let sidebare_content = document.querySelector(`${sidebare}`);
            sidebare_content.style = 'display: block !important';

        } else {
            let sidebare = '.' + arr[i];
            let sidebare_content = document.querySelector(`${sidebare}`);
            sidebare_content.style = 'display: none!important';
        }
    }
}