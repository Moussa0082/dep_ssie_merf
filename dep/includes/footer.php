<?php
  $config = new Config;
  include_once $config->sys_folder.'/database/db_connexion.php';
?>

<?php if(isset($_GET['extra'])){ ?>
<script type="text/javascript">
afficher_msg('extra','<?php echo $_GET['extra']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['insert'])){ ?>
<script type="text/javascript">
afficher_msg('insert','<?php echo $_GET['insert']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['del'])){ ?>
<script type="text/javascript">
afficher_msg('del','<?php echo $_GET['del']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['edit'])){ ?>
<script type="text/javascript">
afficher_msg('edit','<?php echo $_GET['edit']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['update'])){ ?>
<script type="text/javascript">
afficher_msg('update','<?php echo $_GET['update']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['import'])){ ?>
<script type="text/javascript">
afficher_msg('import','<?php echo $_GET['import']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['statut'])){ ?>
<script type="text/javascript">
afficher_msg('statut','<?php echo $_GET['statut']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['doublon'])){ ?>
<script type="text/javascript">
afficher_msg('doublon','<?php echo $_GET['doublon']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['auth'])){ ?>
<script type="text/javascript">
afficher_msg('auth','<?php echo $_GET['auth']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['pass'])){ ?>
<script type="text/javascript">
afficher_msg('pass','<?php echo $_GET['pass']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['mail'])){ ?>
<script type="text/javascript">
afficher_msg('mail','<?php echo $_GET['mail']; ?>');
</script>
<?php }?>