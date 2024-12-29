<?php
include './header.php';

// Load products from JSON
$products = json_decode(file_get_contents('./product.json'), true) ?? [];

// Filter products by category
$category = $_GET['category'] ?? 'all';
if ($category !== 'all') {
    $products = array_filter($products, function ($product) use ($category) {
        return $product['category'] === $category;  
    });
}

// Search functionality
$search = $_GET['search'] ?? '';
if (!empty($search)) {
    $products = array_filter($products, function ($product) use ($search) {
        return stripos($product['name'], $search) !== false;
    });
}

// Sort functionality
$sort = $_GET['sort'] ?? '';
if ($sort === 'price_asc') {
    usort($products, fn($a, $b) => $a['price'] <=> $b['price']);
} elseif ($sort === 'price_desc') {
    usort($products, fn($a, $b) => $b['price'] <=> $a['price']);
}
?>

<main style="margin-top: 20px;" class="p-4">
    <!-- Filter and Search Form -->
    <form class="mb-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6" method="GET"> <!-- Increased gap to 6 -->
        <select name="category" class="border p-3 rounded w-full">
            <option value="all" <?= $category === 'all' ? 'selected' : '' ?>>All Categories</option>
            <option value="Smartphones" <?= $category === 'Smartphones' ? 'selected' : '' ?>>Smartphones</option>
            <option value="Laptops" <?= $category === 'Laptops' ? 'selected' : '' ?>>Laptops</option>
        </select>
        <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>" class="border p-3 rounded w-full">
        <select name="sort" class="border p-3 rounded w-full">
            <option value="">Sort By</option>
            <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
            <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
        </select>
        <button type="submit" class="bg-red-700 text-white px-4 py-2 rounded w-full sm:w-auto">Apply</button>
    </form>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"> <!-- Increased grid gap -->
        <?php if (empty($products)): ?>
            <p class="text-red-500">No products found.</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="border p-4 rounded shadow-lg flex flex-col justify-between h-[450px]"> <!-- Adjusted height -->
                    <img class="h-72 object-cover rounded-t-lg" src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>"> <!-- Increased image height -->
                    <h2 class="text-xl font-bold mt-2"><?= $product['name'] ?></h2>
                    <p class="text-gray-700 mt-2"><?= $product['description'] ?></p>
                    <ul class="text-sm text-gray-600 mt-2">
                        <?php foreach ($product['specifications'] as $key => $value): ?>
                            <li><strong><?= ucfirst($key) ?>:</strong> <?= htmlspecialchars($value) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <p class="text-green-600 font-semibold mt-2">Price: $<?= $product['price'] ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php include './footer.php'; ?>
