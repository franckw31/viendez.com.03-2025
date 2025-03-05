<!-- debut content -->
<?php include('/panel/include/config.php'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" />
<div class="main-content">
    <div class="wrap-content container" id="container">
        <div class="container-fluid container-fullw bg-white">
            <div class="col-md-12">
                <div class="row margin-top-30">
                    <div class="panel-white">
                        <div class="panel-body">
                            <main>
                                <div class="container-fluid px-4">
                                    <h1 class="mt-4">Liste des membres</h1>
                                    <ol class="breadcrumb mb-4">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a>
                                        </li>
                                        <li class="breadcrumb-item active">Liste des membres
                                        </li>
                                    </ol>
                                    <table id="datatablesSimple">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Pseudo</th>
                                                <th>Email</th>
                                                <th>Telephone</th>
                                                <th>Date Inscription</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $ret = mysqli_query($con, "select * from membres ORDER by pseudo");
                                            $cnt = 1;
                                            while ($row = mysqli_fetch_array($ret)) { ?>
                                            <tr>
                                                <td>
                                                    <?php echo $row['id-membre']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['pseudo']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['email']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['telephone']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['posting_date']; ?>
                                                </td>
                                                <td>
                                                    <a href="voir-membre.php?id=<?php echo $row['id-membre']; ?>">
                                                        <i class="fa fa-edit"></i></a>
                                                    <a href="liste-membres.php?id=<?php echo $row['id-membre']; ?>"
                                                        onClick="return confirm('Do you really want to delete');"><i
                                                            class="fa fa-trash" aria-hidden="true"></i></a>
                                                </td>
                                            </tr>
                                            <?php $cnt = $cnt + 1;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </main>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
<script src="../js/datatables-simple-demo.js"></script>
<!-- fin content -->