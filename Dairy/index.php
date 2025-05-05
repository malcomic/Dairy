<!DOCTYPE html>
<html lang="en" class="h-100">
<?php require_once('config.php'); ?>
<?php require_once('inc/header.php') ?>
<body class="hold-transition layout-top-nav dark-mode">
    <div class="wrapper">
        <?php require_once('inc/topBarNav.php') ?>
        
        <?php $page = isset($_GET['page']) ? $_GET['page'] : 'portal'; ?>
        
        <!-- Content Wrapper with gradient background -->
        <div class="content-wrapper bg-gradient-primary" style="min-height: 567.854px;">
            <!-- Animated content header -->
            <div class="content-header">
                <div class="container">
                    <div class="animated-float">
                        <h1 class="text-white mb-0">Welcome to Our Platform</h1>
                        <p class="text-white-50">Your one-stop solution for all needs</p>
                    </div>
                </div>
            </div>
            
            <!-- Main content with glassmorphism effect -->
            <section class="content">
                <div class="container">
                    <div class="glass-card p-4 rounded-lg shadow-lg">
                        <?php 
                            if(!file_exists($page.".php") && !is_dir($page)){
                                include '404.html';
                            }else{
                                if(is_dir($page))
                                    include $page.'/index.php';
                                else
                                    include $page.'.php';
                            }
                        ?>
                    </div>
                </div>
            </section>
            
            <!-- Modern modals with animations -->
            <div class="modal fade" id="confirm_modal" role='dialog'>
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-gradient-danger text-white">
                            <h5 class="modal-title">Confirmation</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="delete_content"></div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-danger btn-lg px-4" id='confirm' onclick="">Continue</button>
                            <button type="button" class="btn btn-secondary btn-lg px-4" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="uni_modal" role='dialog'>
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-gradient-primary text-white">
                            <h5 class="modal-title"></h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-primary btn-lg px-4" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
                            <button type="button" class="btn btn-secondary btn-lg px-4" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="uni_modal_right" role='dialog'>
                <div class="modal-dialog modal-full-height modal-md" role="document">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-gradient-info text-white">
                            <h5 class="modal-title"></h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span class="fa fa-arrow-right"></span>
                            </button>
                        </div>
                        <div class="modal-body">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="viewer_modal" role='dialog'>
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content bg-transparent border-0">
                        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-dismiss="modal">
                            <span class="fa fa-times fa-2x"></span>
                        </button>
                        <div class="text-center">
                            <img src="" alt="" class="img-fluid rounded-lg shadow-lg">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer with gradient -->
        <?php require_once('inc/footer.php') ?>
    </div>
    
    <!-- Add these CSS styles to your header.php or in a style tag -->
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .bg-gradient-danger {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .bg-gradient-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .animated-float {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .dark-mode {
            background-color: #1a1a2e;
            color: #f8f9fa;
        }
        
        .btn-lg {
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-lg:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .shadow-lg {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
    </style>
    
    <!-- Optional: Add animation library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script>
        AOS.init();
    </script>
</body>
</html>