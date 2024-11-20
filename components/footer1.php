<style>
    .footer {
        background-color: #333;
        color: #fff;
        padding: 10px 0; /* Reduce top and bottom padding */
    }

    .grid {
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
    }

    .contact-item {
        flex: 1;
        padding: 5px; /* Reduce padding */
        margin: 1px; /* Reduce margin */
        text-align: center;
    }

    .contact-item h3 {
        font-size: 1.5em;
        margin-bottom: 5px; /* Reduce bottom margin */
    }

    .contact-item p {
        margin: 2px 0; /* Reduce top and bottom margin */
        font-style: italic;
        color: yellow;
    }

    .credit {
        text-align: center;
        margin-top: 5px; /* Reduce top margin */
        font-size: 12px; /* Set the font size for the copyright section */
    }

    .loader {
        text-align: center;
        margin-top: 10px; /* Reduce top margin */
    }

    .contact-item h3 {
        font-size: 20px; /* Set the font size for h3 */
        margin-bottom: 5px;
    }

    .contact-item p {
        font-size: 12px; /* Set the font size for p */
        margin: 2px 0;
    }

    .contact-item a {
        color: #fff;
        font-style: italic;
    }
</style>

<footer class="footer">

   <section class="grid">

      <div class="contact-item">
         <h3>Contact Us</h3>
         </i><p>Email: <a href="njugunaian6@gmail.com">njugunaian6@gmail.com</a></p>
         <p>Phone: <a href="tel:+254 757765212">+254 757765212</a></p>
         <p>Address: <a href="#">Nairobi, Kenya - 00100</a></p>
      </div>

      <div class="contact-item">
         <h3>Opening Hours</h3>
         <p>Monday to Sunday</p>
         <p>04:00am to 01:00am</p>
      </div>

      <div class="contact-item">
         <h3>Follow Us</h3>
         <p>Stay connected on social media for offers and updates!</p>
         <!-- Add  social media links -->
      </div>

   </section>

   <div class="credit">Copyright &copy; <?= date('Y'); ?>  <span>Restaurant Management System</span></div>

</footer>

<div class="loader">
   <img src="images/loader.gif" alt="">
</div>


