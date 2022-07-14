document.addEventListener("DOMContentLoaded", async function() {

    const data = await get_data('http://your-expenses.com/api/admin-panel-data');
    const name = data['user-data']['user_name'];
    const photo = data['user-data']['photo'];

    console.log(name);
    nameAndPhotoLoader(name, photo);
    dropDownMenu();

});