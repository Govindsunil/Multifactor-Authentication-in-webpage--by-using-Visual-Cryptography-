const container = document.querySelector(".container");
const loginsec = document.querySelector(".login-section");
const loginlink = document.querySelector(".login-link");
const registerlink = document.querySelector(".register-link");
const btnPopup = document.querySelector(".btnLogin-popup");
const iconClose = document.querySelector('.icon-close');

registerlink.addEventListener("click", () => {
  loginsec.classList.add("active");
});
loginlink.addEventListener("click", () => {
  loginsec.classList.remove("active");
});
btnPopup.addEventListener("click", () => {
  container.classList.add("active-popup");
});
iconClose.addEventListener("click", () => {
  container.classList.remove("active-popup");
});



function eyekey() {
        var x = document.getElementById("password");
        var y = document.getElementById("hide1");
        var z = document.getElementById("hide2");

        if (x.type === "password") {
          x.type = "text";
          y.style.display = "block";
          z.style.display = "none";
        } else {
          x.type = "password";
          y.style.display = "none";
          z.style.display = "block";
        }
      }


      function eyekey2() {
        var x = document.getElementById("passwords");
        var y = document.getElementById("hidereg1");
        var z = document.getElementById("hidereg2");

        if (x.type === "password") {
          x.type = "text";
          y.style.display = "block";
          z.style.display = "none";
        } else {
          x.type = "password";
          y.style.display = "none";
          z.style.display = "block";
        }
      }

      var modal = document.getElementById("myModal");
      var modal1 = document.getElementById("myModal1");

      function openModal() {
        modal.style.display = "block";
      }

      function closeModal() {
        modal.style.display = "none";
      }
      function openModal1() {
        modal1.style.display = "block";
      }

      function closeModal1() {
        modal1.style.display = "none";
      }

      window.onclick = function (event) {
        if (event.target == modal) {
          closeModal();
        }
        if (event.target == modal1) {
          closeModal1();
        }
      };