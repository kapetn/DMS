<?php
class fckeditor{
	function new_fck($columnname, $width=700, $height=350){
		echo "
		<script type='text/javascript' language='javascript'>
		<!--
		  var oFCKeditor = new FCKeditor('$columnname') ;
		  oFCKeditor.BasePath = '../FCKeditor/' ;
		  //oFCKeditor.BasePath = 'http://www.carner.gr/demo/FCKeditor/' ;
		  oFCKeditor.Width = $width;
		  oFCKeditor.Height = $height;
		  oFCKeditor.Value='';
		  oFCKeditor.Create();
		-->
		</script>
		    ";
	}//end method new_fck
	
	 
	function update_fck($columnname, $columndata, $width=700, $height=350, $disabled=false){
		?>
		<script type="text/javascript" language="javascript">                                     
		<!--                                                                                      
		  var oFCKeditor = new FCKeditor('<?=$columnname;?>') ;
		  //oFCKeditor.BasePath = '../../../FCKeditor/' ;                                         
		  oFCKeditor.BasePath = '../FCKeditor/' ;                                                 
		  oFCKeditor.Width = <?=$width?>;                                                                 
		  oFCKeditor.Height = <?=$height?>;
		  oFCKeditor.Value="<?php echo preg_replace("/\r?\n/", "\\n", addslashes($columndata) );  ?>";
		  //oFCKeditor.EditorDocument.body.contentEditable='false'; 
		  //oFCKeditor.EditorDocument.designMode='off';
<?php
		if ($disabled==true){
?>		
		  oFCKeditor.EditorDocument.body.disabled=true ;	
<?php		  
		}		
?>		  
		  oFCKeditor.Create() ;                                                                   
		-->                                                                                       
		</script>                                                                                 
		<?php
	}//end method update_fck

}//end class
?>