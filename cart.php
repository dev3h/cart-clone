<?php

require_once 'db.php';
$sql = "SELECT * FROM products";
$products = mysqli_query($conn, $sql);

// Page
$a = (isset($_GET['a'])) ? $_GET['a'] : 'home';

require_once 'class.Cart.php';

// Initialize cart object
$cart = new Cart([
	// Maximum item can added to cart, 0 = Unlimited
	'cartMaxItem' => 0,

	// Maximum quantity of a item can be added to cart, 0 = Unlimited
	'itemMaxQuantity' => 5,

	// Do not use cookie, cart items will gone after browser closed
	'useCookie' => false,
]);

// Shopping Cart Page
if ($a == 'cart') {
	$cartContents = '
	<div class="alert alert-warning">
		<i class="fa fa-info-circle"></i> Không có sản phẩm nào trong giỏ hàng.
	</div>';

	// Empty the cart
	if (isset($_POST['empty'])) {
		$cart->clear();
	}

	// Add item
	if (isset($_POST['add'])) {
		foreach ($products as $product) {
			if ($_POST['id'] == $product['id']) {
				break;
			}
		}

		$cart->add($product['id'], $_POST['qty'], [
			'price' => $product['price'],
		]);
	}

	// Update item
	if (isset($_POST['update'])) {
		foreach ($products as $product) {
			if ($_POST['id'] == $product['id']) {
				break;
			}
		}

		$cart->update($product['id'], $_POST['qty'], [
			'price' => $product['price'],
		]);
	}

	// Remove item
	if (isset($_POST['remove'])) {
		foreach ($products as $product) {
			if ($_POST['id'] == $product['id']) {
				break;
			}
		}

		$cart->remove($product['id'], [
			'price' => $product['price'],
		]);
	}

	if (!$cart->isEmpty()) {
		$allItems = $cart->getItems();

		$cartContents = '
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th class="col-md-7">Sản phẩm</th>
					<th class="col-md-3 text-center">Số lượng</th>
					<th class="col-md-2 text-right">Giá</th>
				</tr>
			</thead>
			<tbody>';

		foreach ($allItems as $id => $items) {
			foreach ($items as $item) {
				foreach ($products as $product) {
					if ($id == $product['id']) {
						break;
					}
				}

				$cartContents .= '
				<tr>
					<td>' . $product['name'] . '</td>
					<td class="text-center"><div class="form-group"><input type="number" value="' . $item['quantity'] . '" class="form-control quantity pull-left" style="width:100px"><div class="pull-right"><button class="btn btn-default btn-update" data-id="' . $id . '"><i class="fa fa-refresh"></i> Update</button><button class="btn btn-danger btn-remove" data-id="' . $id . '"><i class="fa fa-trash"></i></button></div></div></td>
					<td class="text-right">$' . $item['attributes']['price'] . '</td>
				</tr>';
			}
		}

		$cartContents .= '
			</tbody>
		</table>

		<div class="text-right">
			<h3>Total:<br />$' . number_format($cart->getAttributeTotal('price'), 2, '.', ',') . '</h3>
		</div>

		<p>
			<div class="pull-left">
				<button class="btn btn-danger btn-empty-cart">Xóa toàn bộ</button>
			</div>
			<div class="pull-right text-right">
				<a href="?a=home" class="btn btn-default">Tiếp tục mua sắm</a>
				<a href="?a=checkout" class="btn btn-danger">Đặt hàng</a>
			</div>
		</p>';
	}
}
