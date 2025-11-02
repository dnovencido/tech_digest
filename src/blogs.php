<?php
    include "helpers/session.php";
    include "helpers/require_login.php";
    include "helpers/preview.php";
    include "models/blog.php";
    include "models/category.php";

    $categories = get_categories();
    
    if (isset($_GET['page_no'])) {
        $page_no = $_GET['page_no'];
    } else {
        $page_no = 1;
    }

    $blogs = [];
    
    $total_records_per_page = 10; // defines the number of records (blogs) to be displayed on each page
    $offset = ($page_no - 1) * $total_records_per_page; //determine the starting point (index) of records to be fetched
	$previous_page = $page_no - 1; // previous page
	$next_page = $page_no + 1; // next page

	$total_records = get_total_number_records();
    $total_no_of_pages = ceil($total_records / $total_records_per_page); // determines the number of pages to be displayed

    if (isset($_SESSION['id'])) {
        $blogs = get_all_blogs($filter = ['user_id' => $_SESSION['id']], $pagination = ['offset'=> $offset, $total_records_per_page = 10, 'total_records_per_page' => $total_records_per_page]);
    }

?>
<?php include "layouts/_header.php";?>
<main>
    <section id="blog" class="container account-main">
        <?php if(isset($_SESSION['flash_message'])) { ?>
            <?php include "layouts/_messages.php"; ?>
        <?php } ?>
        <div id="inner-blogs">
            <div id="blog-navigation">
                <?php include "layouts/_account-navigation.php" ?>
            </div>
            <div id="blog-list">
                <h3>Blogs</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($blogs)) { ?>
                            <?php foreach($blogs as $row) { ?>
                                <tr>
                                    <td><a href="/blogs/<?= $row['id'] ?>/view"><?= display_blog_preview($row['title'], 90)?></a></td>
                                    <td><?= ucfirst($row['status']) ?></td>
                                    <td><?= !empty($row['updated_at']) ? date('M d, Y @ h:i a', strtotime($row['updated_at'])) : '-' ?></td>
                                    <td><?= date('M d, Y @ h:i a', strtotime($row['date_created'])) ?></td>
                                    <td class="action-buttons">
                                        <a href="blogs/<?= $row['id'] ?>/edit" class="btn-info">Edit</a>
                                        <a href="#" class="btn-delete btn-danger" data-id="<?= $row['id'] ?>">Delete</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <td colspan="5">No blogs to be displayed...</td>
                        <?php } ?>
                    </tbody>
                </table>    
                <!-- Pagination -->
                <?php if(!empty($blogs)) { ?>
                    <div id="pagination">
                        <ul>
                            <li class="page-item <?= ($page_no <= 1) ? "disabled" : "" ?>"> 
                                <a href="<?= ($page_no > 1) ? '?page_no='.$previous_page : '' ?>" class="page-link">Previous</a>
                            </li>
                                        
                            <!-- Page numbers -->
                            <?php for ($counter = 1; $counter <= $total_no_of_pages; $counter++) { ?>
                                <?php if ($counter == $page_no) { ?>
                                    <li class="page-item"><a class="page-link active"> <?= $counter ?> </a></li>
                                <?php } else { ?>
                                    <li class="page-item"><a href='?page_no=<?=$counter?>' class="page-link"><?= $counter ?></a></li>
                                <?php } ?>
                            <?php } ?>

                            <!-- Next and last button -->
                            <?php if($page_no < $total_no_of_pages) { ?>
                                <li class="page-item <?= ($page_no >= $total_no_of_pages) ? "disabled" : "" ?>">
                                    <a href="<?= ($page_no < $total_no_of_pages) ?  "?page_no=".$next_page : ""?>" class="page-link"> Next  &rsaquo;&rsaquo; </a>
                                </li>
                                <li class="page-item"><a href="?page_no=<?=$total_no_of_pages?>" class="page-link">Last</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</main>
<?php include "layouts/_footer.php";?>