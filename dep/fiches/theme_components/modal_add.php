<div class="modal fade hmodal-info" id="myModal_add" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="color-line"></div>
      <div class="modal-header"> <div class="btn-xs move_modal" title="Deplacer" style="cursor: move"><span class="glyphicon glyphicon-move"></span></div><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><img src="./images/close.png" alt="Fermer"></button> <h4 class="modal-title" id="modal-title_add"><p class="dancing-dots-text" align="center" style="padding:5px; vertical-align: middle;">Chargement en cours<span><span>•</span><span>•</span><span>•</span></span></p></h4> </div>
      <div class="modal-body" id="modal-body_add"><p class="dancing-dots-text" align="center" style="padding:5px; vertical-align: middle;">Chargement en cours<span><span>•</span><span>•</span><span>•</span></span></p></div>
      <div class="modal-footer"> <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button></div>
      </div>
  </div>
</div>
<style>
.move_modal{float:left;font-size:21px;font-weight:700;line-height:1;color:#000;text-shadow:0 1px 0 #fff;filter:alpha(opacity=20);opacity:.8}.modal-header .move_modal{margin-top:-16px;margin-left:-25px}
</style>
<script>
$(".modal-dialog").draggable({handle: ".move_modal"});
$(document).ready(function(){$('.modal-content').resizable({});});$('#myModal_add').on('show.bs.modal', function () {$(this).find('.modal-body').css({'max-height':'100%'});});
</script>