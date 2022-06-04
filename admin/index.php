<?php
session_start();
require "../config/config.php";

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
?>

<?php include 'header.php'; ?>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Blog Listings</h3>
          </div>

          <!-- fetch data from Mysql  -->
          <?php
          if (!empty($_GET['pageno'])) {
            $pageno = $_GET['pageno'];
          } else {
            $pageno = 1;
          }
          $numberOfrecord = 1;
          $offset = ($pageno - 1) * $numberOfrecord;

          if (empty($_POST['search'])) {
            $pdostatement = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
            $pdostatement->execute();
            $result = $pdostatement->fetchAll();
            $total_pages = ceil(count($result) / $numberOfrecord);

            $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numberOfrecord");
            $stmt->execute();
            $result = $stmt->fetchAll();
          } else {
            $searchKey = $_POST['search'];
            $pdostatement = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC");
            $pdostatement->execute();
            $result = $pdostatement->fetchAll();
            $total_pages = ceil(count($result) / $numberOfrecord);

            $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numberOfrecord");
            $stmt->execute();
            $result = $stmt->fetchAll();
          }
          ?>

          <!-- /.card-header -->
          <div class="card-body">
            <div class="mb-4">
              <a href="add.php" type="button" class="btn btn-success">New Blog Post</a>
            </div>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Title</th>
                  <th>Content</th>
                  <th style="width: 40px">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($result) {
                  $i = 1;
                  foreach ($result as $value) { ?>
                    <tr>
                      <td><?= $i ?></td>
                      <td><?= $value['title'] ?></td>
                      <td>
                        <?= substr($value['content'], 0, 50) ?>
                      </td>
                      <td>
                        <div class="btn-group">
                          <div class="container">
                            <a href="edit.php?id=<?= $value['id'] ?>" type="button" class="btn btn-warning">Edit</a>
                          </div>
                          <div class="container">
                            <a href="delete.php?id=<?= $value['id'] ?>" type="button" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</a>
                          </div>
                        </div>
                      </td>
                    </tr>
                <?php
                    $i++;
                  }
                }
                ?>


              </tbody>
            </table><br />

            <!-- pagination  -->
            <nav aria-label="Page navigation example" style="float: right;">
              <ul class="pagination">
                <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                <li class="page-item <?php if ($pageno <= 1) {
                                        echo 'disabled';
                                      } ?>"><a class="page-link" href="<?php if ($pageno <= 1) {
                                                                          echo '#';
                                                                        } else {
                                                                          echo '?pageno=' . ($pageno - 1);
                                                                        } ?>">Previous</a></li>
                <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                <li class="page-item <?php if ($pageno >= $total_pages) {
                                        echo 'disabled';
                                      } ?>"><a class="page-link" href="<?php if ($pageno >= $total_pages) {
                                                                          echo '#';
                                                                        } else {
                                                                          echo '?pageno=' . ($pageno + 1);
                                                                        } ?>">Next</a></li>
                <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages ?>">Last</a></li>
              </ul>
            </nav>
          </div>
          <!-- /.card-body -->


        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->


    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<?php include "footer.html"; ?>