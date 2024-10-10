<script>

let profilepic = document.getElementById("profile-pic");
let inputFile = document.getElementById("input-file");

inputFile.onchange = function() {
    profilepic.src = URL.createObjectURL(inputFile.files[0]);
}

let profile = document.getElementById("profile");
profile.addEventListener("click", () => {
    inputFile.click();
})
</script>
 
 <!-- Bootstrap core JavaScript-->
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../assets/js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="../assets/vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="../assets/js/demo/chart-area-demo.js"></script>
<script src="../assets/js/demo/chart-pie-demo.js"></script>

<!-- Page level plugins -->
<script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="../assets/js/demo/datatables-demo.js"></script>

</body>

</html>
<script>
        var btnvar = document.querySelectorAll('.addFav');

        for (let i = 0; i < btnvar.length; i++) {
            btnvar[i].addEventListener("click", (e) => {
                toggle(btnvar[i])
            })
        }

        function toggle(element) {
            var spanElement = element.querySelector('span');
            var ielement = element.querySelector('i');
            let value = spanElement.innerText;
            if (ielement.id == "1") {
                fetch('favDec.php?id=<?= $user['user_id'] ?>&post_id=' + element.id)
                    .then(response => response.json())
                    .then(data => {
                        location.reload();
                    })
                    .catch(error => console.error('Error:', error));

            } else {

                fetch('favInc.php?id=<?= $user['user_id'] ?>&post_id=' + element.id)
                    .then(response => response.json())
                    .then(data => {
                        location.reload();
                    })
                    .catch(error => console.error('Error:', error));
            }
        }
    </script>