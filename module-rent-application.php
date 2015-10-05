<?php
  // form-rent.php
  ?>
<div class='module-rent-application' role='widget' aria-describedby='rent-application-description' >

  <button class='close'>
    <span class='label'>Close Form</span>
    <?php get_template_part( "img/inline", "close-icon.svg" ); ?>
  </button>

  <div class='rent-application ui-state' data-ui-state='apply'>
    <h1>Rent Now</h1>
    <p id="rent-application-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque feugiat vel orci vitae consectetur. Praesent sollicitudin et sem ut ornare. Proin feugiat lacinia ullamcorper.</p>
    <form id='rent-application'>
      <div class='form-inner'>
        <div class='form-control'>
          <label for='name'>Name</label>
          <input type='text' name='name' placeholder='Your Name' />
        </div>
        <div class='form-control'>
          <label for='email'>Email</label>
          <input type='email' name='email' placeholder='Email' />
        </div>
        <div class='form-control'>
          <label for='phone'>Name</label>
          <input type='text' name='phone' placeholder='Phone' />
        </div>
      </div>
      <input class='btn' type='submit' />
    </form>
  </div>

  <div class='submit-response ui-state' data-ui-state='response' aria-hidden='true' hidden>
    <h1>Thank you</h1>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque feugiat vel orci vitae consectetur. Praesent sollicitudin et sem ut ornare. Proin feugiat lacinia ullamcorper.</p>
  </div>

</div> 
<!-- /div.rent-application -->