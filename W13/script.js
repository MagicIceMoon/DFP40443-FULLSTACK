const showBtn = document.getElementById("loadMessage");
const systemBtn = document.getElementById("loadSystem");
const SQLBtn = document.getElementById("checkDB");
const rolesBtn = document.getElementById("checkRoles");
const checkUsers = document.getElementById("checkUsers");

if(systemBtn) {
    systemBtn.addEventListener("click",function(){
        fetch("message.php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("result").innerHTML = "<p>" + data + "</p>";
        })
    })
}
if(showBtn) {
    showBtn.addEventListener("click", function() {
        fetch("message.php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("result").innerHTML = "<p style='color:red;'>" + data + "</p>";
        })
    })
}
if(SQLBtn) {
    SQLBtn.addEventListener("click", function() {
        fetch("count.php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("result").innerHTML = "<p style='color:red;'>" + data + "</p>";
        })
    })
}
if(checkUsers) {
    checkUsers.addEventListener("click", function() {
        fetch("users_list.php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("result").innerHTML = "<p style='color:red;'>" + data + "</p>";
        })
    })
}
if(rolesBtn) {
    rolesBtn.addEventListener("click", function() {
        fetch("roles.php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("result").innerHTML = "<p style='color:red;'>" + data + "</p>";
        })
    })
}