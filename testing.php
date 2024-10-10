<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .popup {
    display: none; 
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgb(0,0,0); 
    background-color: rgba(0,0,0,0.4); 
}

.popup-content {
    background-color: #fefefe;
    margin: 15% auto; 
    padding: 20px;
    border: 1px solid #888;
    width: 80%; 
    max-width: 400px;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}
    </style>
</head>

<body>
<img src="./assets/img/1.png" alt="" class="" onclick="myFunction()" style="width: 300px;">
<div id="popup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="hidePopup()">&times;</span>
            <p id="popup-description">This is the description of the image.</p>
        </div>
    </div>

</body>
</html>
<script>
function myFunction() {
    var popup = document.getElementById('popup');
    popup.style.display = "block";
}

function hidePopup() {
    var popup = document.getElementById('popup');
    popup.style.display = "none";
}
</script>

