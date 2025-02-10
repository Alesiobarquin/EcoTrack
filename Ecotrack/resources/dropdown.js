menuOut=false;

$('.dropDownMenu').click(function(){
  if (menuOut){
  		//Menu is visible, so HIDE menu
      $('.menu').animate({
        right: '-30vh'
      },800);
      menuOut = false;
  }else{
  		//Menu is hidden, so SHOW menu
      $('.menu').animate({
        right: 0
      },800);
      menuOut = true;
  }
})

$('.logOut').click(function(){
  let confirmedLogOut = confirm('Are you sure you want to log out?');
  if(confirmedLogOut){
    //Will delete the users login information from the current session
    window.location.href = '../sign_up/logout.php'; 
    console.log('logging out');
  }
  else{
    console.log('Cancelled Log out');
  }
})