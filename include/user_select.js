var j$ = jQuery.noConflict();  
Array.prototype.remove = function(value) {
    if (this.indexOf(value)!==-1) {
       this.splice(this.indexOf(value), 1);
       return true;
   } else {
      return false;
   };
} 

var utenti = new Array();
var uu =  new Array();

function fire(x,y){
    elemento=document.getElementById('check_'+x);	
    j$(elemento).next('span.hidden').html( j$( elemento).is(':checked') ? '1':'0' );    
    if(elemento.checked) {
	    //localStorage.setItem('codicefiscale', x);
    	uu.push(x);
    }else{			
	    a=utenti.remove(x+':'+y);
	    a=uu.remove(x);
		//console.log(elements);
    }								
    var elements = utenti.join(',');
    document.cookie='utenti= '+elements;			
    
    j$.map(table.data(), function(v,i){

        if( x == v[2]  ){

            var cell = table.cell(i,6);

            if( j$.inArray(x,uu) < 0){
                
                var newvalue = cell.data().replace(/<span class="hidden">(.)<\/span>/,'<span class="hidden">0</span>').replace(/\ checked="checked"/,'');
            }
			else{
            
                var newvalue = cell.data().replace(/<span class="hidden">(.)<\/span>/,'<span class="hidden">1</span>').replace(/^<input\ /,'<input checked="checked" ');
            }
     
            cell.data( newvalue );
        }            
    } ) ;

}

var dataSet = [
    ['Trident','Internet Explorer 4.0','Win 95+','4','X'],
    
];

		j$(document).ready(function() {
 
 var s = j$("#sessione").val();
  var r = j$("#rete").val();
  var p = j$("#profili").val();
 		j$('.selezionatutte').click(function(event) {  //on click
               j$('input[type="checkbox"][name="myCheckbox"][class="seleziona"]').each(function() {
                this.checked = ! this.checked;  j$(this).change();
    });

    }); 
    	
 j$.ajax({
    url : M.cfg.wwwroot+"/local/better_user_selector/ajax.php?action=sel&rete="+r+"&profili="+p+"",
    success : function (data) {
    	 	j$('#preloader').fadeOut('slow',function(){j$(this).remove();}); 

        var obj = JSON.parse(data);
        var j=0;
        var desc ="";
        var riga = "";
		var seltutte="";
        for(var i=0;i<obj.length;i++){
       
	   
        	desc="<td><input type='checkbox' name='myCheckbox' class='seleziona' id='check_"+obj[i][1]+"' onchange='fire(\""+obj[i][1]+"\",\""+obj[i][5]+"\")'><span class='hidden'>"+ 0 +"</span></td></tr>";
            riga = obj[i][0]+desc;
        	
        	j$("#example").append(obj[i][0]+desc);
        	}
        	j$("#example").append(seltutte);
        
        j$('#example tfoot th').each( function () {
            var title = j$('#example thead th').eq( j$(this).index() ).text();
            if( title !='Seleziona' )
                j$(this).html( '<input type="text" placeholder="'+title+'" />' );	
            else
                j$(this).html( '<select name="checkFilter"><option selected value="">Select</option><option value="1">Selected</option><option value="0">Not Selected</option></select>' );		
        } );	
        	
        	
        table = j$('#example').DataTable({"bProcessing": true,"deferRender": true});
    
        table.columns().every( function () {
            var that = this; 
            j$( 'input', this.footer() ).on( 'keyup change', function () {
                that.search( this.value ).draw();
            } );
        } );
        table.columns(6).every(function(){
            var that = this;                 
            j$('select', this.footer() ).on('change', function(){
                console.log( this.value );
                that.search( this.value  ).draw();
            });
        });    
        j$(".docenti").append('<br><br><input type=button id="annulla" onclick="window.close();" value=chiudi>');              
    },
    error : function () {
        alert("AJAX ERROR! ");
    }
});

 var j=0;
 j$( "#salva" ).click(function() {
  
  j$.ajax({
    url : M.cfg.wwwroot+"/local/better_user_selector/ajax.php?s="+s+"&action=save",
    success : function (data) {
        
        j++;
    },
    error : function () {
        alert("AJAX ERROR! "+stato);
    }
		
	});

  
});

j$( "#annulla" ).click(function() {
  window.close();
});
    
} );