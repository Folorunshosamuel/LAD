<?php
// forum.php
require 'db_connect.php'; // Database connection

// Pagination variables
$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Search query (if provided)
$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';
$searchSQL = '';
$params = [];

if ($searchQuery !== '') {
    $searchSQL = " AND (p.title LIKE ? OR p.content LIKE ?)";
    $searchTerm = '%' . $searchQuery . '%';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

// Fetch categories
$categories = $db->query("SELECT id, name FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Optional category filter
$categoryFilter = isset($_GET['category']) ? intval($_GET['category']) : null;
if ($categoryFilter) {
    $categorySQL = " AND p.category_id = ?";
    $params[] = $categoryFilter;
} else {
    $categorySQL = "";
}

// Get total post count for pagination
$countStmt = $db->prepare("SELECT COUNT(*) FROM posts p WHERE 1=1 $searchSQL $categorySQL");
$countStmt->execute($params);
$totalPosts = $countStmt->fetchColumn();
$totalPages = ceil($totalPosts / $limit);

// Get posts with search and pagination
$sql = "SELECT p.*, u.fname, u.role, (SELECT COUNT(*) FROM likes WHERE post_id = p.id) AS like_count 
        FROM posts p 
        JOIN users u ON p.user_id = u.id 
        WHERE 1=1 $searchSQL $categorySQL 
        ORDER BY p.created_at DESC 
        LIMIT $limit OFFSET $offset";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch comments for posts
function getComments($db, $postId) {
    $stmt = $db->prepare("SELECT c.*, u.fname FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ? ORDER BY c.created_at ASC");
    $stmt->execute([$postId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forum</title>
    <link rel="stylesheet" href="forum.css">
</head>
    </style>
</head>
<body>
<?php
    include 'header.php'; // Include the header at the beginning of the dashboard
    ?>
<div class="container">
    <aside class="sidebar">
        <h3>Forum Discussions</h3>
        <ul>
            <li><a href="forum.php">#All Discussions</a></li>
            <?php foreach ($categories as $category): ?>
                <li><a href="?category=<?= $category['id'] ?>">#<?= htmlspecialchars($category['name']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </aside>

    <main class="content">
        <!-- Search Form --> 
        <div class="search-box">
            <form method="GET" action="forum.php">
                <input type="text" name="q" placeholder="Search posts..." value="<?= htmlspecialchars($searchQuery) ?>">
                <?php if ($categoryFilter): ?>
                    <input type="hidden" name="category" value="<?= $categoryFilter ?>">
                <?php endif; ?>
                <button type="submit">Search</button>
            </form>
        </div>
        <button class="create-post-btn" onclick="openCreatePostModal()">+ Create Post</button>

        <?php if (empty($posts)): ?>
            <p>No posts available in this category.</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post-card" id="post-<?= $post['id'] ?>">
                    <div class="post-header">
                        <strong><?= htmlspecialchars($post['fname']) ?></strong><br>
                        <small><?= htmlspecialchars($post['role']) ?></small>|
                        <span><?= date('F j, Y, g:i a', strtotime($post['created_at'])) ?></span>
                    </div>
                    <div class="post-body">
                        <h4><?= htmlspecialchars($post['title']) ?></h4>
                        <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                       
                    </div>
                    <div class="post-footer">
                        <button class="bttn" onclick="likePost(<?= $post['id'] ?>)">Like (<span id=\"like-count-<?= $post['id'] ?>\"><?= $post['like_count'] ?></span>)</button>
                        <button class="bttn" onclick="toggleCommentForm(<?= $post['id'] ?>)">Reply</button>
                        <!-- Display Comments -->
                        <?php $comments = getComments($db, $post['id']); ?>
                        <?php if (count($comments) > 0): ?>
                            <a class="bttn" data-bs-toggle="collapse" href="#comments-<?= $post['id'] ?>" role="button" aria-expanded="true" aria-controls="comments-<?= $post['id'] ?>">View Comments</a>
                        <?php endif; ?>
                    </div>
                    <div class="mg-t-5 collapse show comments" id="comments-<?= $post['id'] ?>">
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment">
                                <strong><?= htmlspecialchars($comment['fname']) ?></strong><br>
                                <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Comment Form --> 
                    
                    <div id="comment-form-<?= $post['id'] ?>" class="comment-form" style="display:none;">
                        <textarea id="comment-text-<?= $post['id'] ?>" placeholder="Add a comment..."></textarea>
                        <button onclick="submitComment(<?= $post['id'] ?>)">Submit Comment</button>
                    </div>

                    
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
         <!-- Pagination --> 
         <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a class="<?= ($i == $page) ? 'active' : '' ?>" href="?page=<?= $i ?><?= ($categoryFilter) ? '&category=' . $categoryFilter : '' ?><?= ($searchQuery !== '') ? '&q=' . urlencode($searchQuery) : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    </main>
</div>

<!-- Create Post Modal 
<div id="createPostModal" class="modal">
    <div class="modal-content">
        <h3>Create New Post</h3>
        <label>Title:</label>
        <input class="form-group mb-2" type="text" id="new-post-title" required><br><br>
        <label>Content:</label>
        <textarea class="form-group mb-2" id="new-post-content" required></textarea><br><br>
        <label>Category:</label>
        <select id="new-post-category">
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
            <?php endforeach; ?>
        </select><br><br>
        <button class="bttn" onclick="submitNewPost()">Submit Post</button>
        <button class="bttn" type="button" onclick="closeCreatePostModal()">Cancel</button>
    </div>
</div> -->
<!-- Create Post Modal -->
<div id="createPostModal" class="modal">
  <div class="modal-content">
    <h3>Create New Post</h3>
    <label>Title:</label>
    <input type="text" id="new-post-title" required><br><br>
    <label>Content:</label>
    <!-- Quill Editor Container -->
    <div id="quillEditor" style="height: 150px;"></div><br><br>
    <label>Category:</label>
    <select id="new-post-category">
      <?php foreach ($categories as $category): ?>
        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
      <?php endforeach; ?>
    </select><br><br>
    <button class="bttn" onclick="submitNewPost()">Submit Post</button>
    <button class="bttn" type="button" onclick="closeCreatePostModal()">Cancel</button>
  </div>
</div>

<script>
// Toggle Comment Form
function toggleCommentForm(postId) {
    const form = document.getElementById('comment-form-' + postId);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

// Submit Comment via fetch
function submitComment(postId) {
    const commentText = document.getElementById('comment-text-' + postId).value;
    if (!commentText) {
        alert('Please enter a comment.');
        return;
    }
    fetch('submit_comment.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ post_id: postId, content: commentText })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Reload to show new comment (or update DOM via JS)
        } else {
            alert('Error submitting comment: ' + data.error);
        }
    });
}

// Like a post via fetch
function likePost(postId) {
    fetch('like_post.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ post_id: postId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the like count displayed on the page
            document.getElementById('like-count-' + postId).textContent = data.like_count;
            // Toggle the button text based on action
            let likeButton = document.querySelector('#post-' + postId + ' .like-btn');
            if (data.action === 'like') {
                likeButton.textContent = 'Unlike (' + data.like_count + ')';
            } else {
                likeButton.textContent = 'Like (' + data.like_count + ')';
            }
        } else {
            console.error('Error:', data.error);
        }
    })
    .catch(error => console.error('Fetch error:', error));
}


/* // Open Create Post Modal
function openCreatePostModal() {
    document.getElementById('createPostModal').style.display = 'flex';
}

// Close Create Post Modal
function closeCreatePostModal() {
    document.getElementById('createPostModal').style.display = 'none';
}

// Submit New Post via fetch
function submitNewPost() {
    const title = document.getElementById('new-post-title').value;
    const content = document.getElementById('new-post-content').value;
    const category_id = document.getElementById('new-post-category').value;

    if (!title || !content) {
        alert('Please fill in all fields.');
        return;
    }

    fetch('create_post.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ title: title, content: content, category_id: category_id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Reload to display the new post
        } else {
            alert('Error creating post: ' + data.error);
        }
    });
} */

// Initialize Quill Editor for the create post modal
var quill = new Quill('#quillEditor', {
  theme: 'snow'
});

// Open and close modal functions
function openCreatePostModal() {
  document.getElementById('createPostModal').style.display = 'flex';
}

function closeCreatePostModal() {
  document.getElementById('createPostModal').style.display = 'none';
}

// Updated submitNewPost function to get content from Quill Editor
function submitNewPost() {
  const title = document.getElementById('new-post-title').value;
  const content = quill.root.innerHTML;
  const category_id = document.getElementById('new-post-category').value;

  if (!title || !content) {
    alert('Please fill in all fields.');
    return;
  }

  fetch('create_post.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ title: title, content: content, category_id: category_id })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      location.reload(); // Reload to display the new post
    } else {
      alert('Error creating post: ' + data.error);
    }
  });
}
</script>
<script src="js/azia.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
