<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Git WebAdmin</title>

    <meta name="description" content="Simply my own git webadmin.">
    <meta name="author" content="mcka-dev">

    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="bower_components/bootstrap-select/dist/css/bootstrap-select.css">
    <script src="bower_components/modernizr/modernizr.js"></script>
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!--[if lt IE 8]>
    <div class="alert alert-warning alert-dismissible browserupgrade" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</div>
	<![endif]-->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Git WebAdmin</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <li><a href="https://github.com/mcka-dev/GitWebAdmin">Github</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="form-horizontal">
			<h3>Create A New Repository</h3>
            <?php
                if (count($repo_list) > 1) {
                     echo '
                            <div class="col-xs-4 col-sm-2 without-padding">
                                <select class="selectpicker form-control" name="repository" >';
                                   foreach($repo_list as $key => $repo) {
                                        echo '<option>'.$key.'</option>';
                                   };
                     echo '     </select>
                            </div>';
                };
            ?>
            <div class="col-xs-6 col-sm-7 <?php if (count($repo_list) == 1) {echo "without-padding"; } ?>">
                <input id="input" type="text" class="form-control" name="repo_name" value="" placeholder="example.git">
            </div>

            <button class="btn btn-success" id="btn_create">
            	<span class="glyphicon glyphicon-plus"></span>
            	<span class="hidden-xs"> Create repository</span>
        	</button>

            <div class="messages"></div>

            <div class="table-responsive borderless">
                <?php foreach($repo_list as $key => $repo):?>
                <h2>Repositories in <?php echo '"'.$key.'"'; ?></h2>
                <table class="table table-striped">

                    <tbody>
                    <?php
                        $counter = 0;
                        foreach($repo as $path => $dir):
                    ?>
                        <tr>
                            <td>
                            <?php
                               $links = ($config->get('git', 'links'));

                               if ($links && is_array($links) && array_key_exists($key, $links)) {
                                   echo '<a target="_blank" href="'.$links[$key].'/'.$repo->getSubPathName().'">'.$repo->getSubPathName().'</a>';
                               }
                               else
                               {
                                   echo '<span>'.$repo->getSubPathName().'</span>';
                               }
                            ?>
                            </td>
                            <td class="text-right">
                                <div class="btn-group flex-btn-group-container">
                                    <a class="btn btn-danger btn-sm" data-repository="<?php echo $key; ?>" data-repo-name="<?php echo $repo->getSubPathName(); ?>">
                                        <span class="glyphicon glyphicon-trash"></span>
                                        <span class="hidden-xs"> Delete</span>
                                    </a>
                                </div>
                           </td>
                        </tr>
                    <?php
                        $counter++;
                        endforeach;
                        if ($counter== 0) {
                            echo '<p class="text-warning">Empty folder</p>';
                        };
                    ?>
                    </tbody>
                </table>
                <?php endforeach;?>
            </div>
 		</div>
    </div>

    <footer class="footer">
      <div class="container">
        <p class="text-muted">&copy; <a href="https://github.com/mcka-dev">mcka-dev</a> 2016</p>
      </div>
    </footer>

	<div id="modal-from-dom" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="pulseWarning text-center" style="display: block;">
                        <span class="glyphicon glyphicon-alert pulseWarningIns"></span>
                    </div>
                    <h2 id="modal-title" class="modal-title text-center"></h2>
				</div>
				<div class="modal-body text-center">
					<p class="lead text-danger">Are you sure? This can't be undone.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button id="btn_delete" type="button" class="btn btn-danger">Yes, delete it!</button>
				</div>
			</div>
		</div>
	</div>

	<script src="bower_components/jquery/dist/jquery.min.js"></script>
	<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="bower_components/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="js/script.js"></script>

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
    <!--
    <script>
        (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
        function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
        e=o.createElement(i);r=o.getElementsByTagName(i)[0];
        e.src='//www.google-analytics.com/analytics.js';
        r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
        ga('create','UA-XXXXX-X','auto');ga('send','pageview');
    </script>
	-->
</body>
</html>