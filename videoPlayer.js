var player = videojs('#myPlayer'  ,{

    controls : true ,
    fluid : true ,
    playbackRates : [0.5,1,1.5,2] , 
    userActions : {

        hotkeys :true ,
    }
});



var lesson_name = document.querySelector('#myPlayer').getAttribute('lesson_name');
var stage = document.querySelector('#myPlayer').getAttribute('stage');



//first ajax req to get the time instant(s) to register on the video and then trigger the pause events manually at them

 //ajax 
 var time_instants ; // GLOBAL variable 
 var question_answer ; // GLOBAL variable 
 var time_instant_preserve  ; 
 xhttp =new XMLHttpRequest();
 xhttp.onreadystatechange = function() {
     if (this.readyState == 4 && this.status == 200) {
     

    
     time_instants = xhttp.responseText.split(" ").slice(1);
    // remove duplicat instancs 
    time_instants = time_instants.filter((x, i, a) => a.indexOf(x) == i)
    // convert to int 
    time_instants = time_instants.map((el) => parseInt(el))
    // sort 
    time_instants = time_instants.sort();    
    

    //  console.log(time_instants);
    
    
 }

}
xhttp.open("GET", `inVideo.php?do=time_instant&lesson_name=${lesson_name}&stage=${stage}`, true);
xhttp.send();

//second 

var timeCheck = function() {

    var requset_response_overhead_time = 0.1 ;
    for (i = 0; time_instants && (i < time_instants.length ); i++) {
        if ((player.currentTime())  >= (time_instants[i]-requset_response_overhead_time) && time_instants ) {
            

            time_instant_preserve = time_instants[0]; 
            time_instants.shift();
            // console.log(player.currentTime());
            // console.log(time_instant_preserve);
           
            player.off('timeupdate', timeCheck);     
            player.trigger('quiz_event');
                }
    }
    
    };


player.on('quiz_event', function(){


player.trigger('pause');
//ajax 
xhttp =new XMLHttpRequest();
xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    
     // where the magic happens
    var contentEl = document.createElement('div');
    contentEl.style.background = 'black' ; 
    contentEl.style.height = '100%' ; 
    contentEl.style.overflowY = 'auto' ;
    

    // probably better to just build the entire thing via DOM methods
    contentEl.innerHTML = xhttp.responseText;
    // console.log(xhttp.responseText);
    //alert(player.currentTime());
    var modal = player.createModal(contentEl);
    modal.on('modalclose', function() {
        player.play();
        player.on('timeupdate', timeCheck);    
    });

    player.on('play', function() {
    modal.close();
    });

    
    var subButton = document.querySelector('#sub');
    var contButton = document.querySelector('#cont');
    contButton.onclick = function () {
        modal.close();

    }
    subButton.onclick = function () {
       
    answers =  document.getElementsByClassName("Qans") ;     
        for(let i = 0 ; i < answers.length ; i++ ){
            answers[i].style.color = "yellow" ; 
        }


            };

  
    }
  }; 

  if(player.currentTime() < player.duration() ){

    xhttp.open("GET", `inVideo.php?do=content&lesson_name=${lesson_name}&stage=${stage}&time_instant=${time_instant_preserve}`, true);
    xhttp.send();
  }

  
 




});
player.on('timeupdate', timeCheck);



player.on('ended', function() {
    //console.log("it doesn't work");

    player.off('pause'); 
   
    // this.dispose();
    // modal.close();
    });