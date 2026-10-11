<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "quanly_tainghe");
if (!$conn) {
    die("Lỗi kết nối: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");

$dsKieu = ["In-ear", "Over-ear", "On-ear", "True wireless"];
$dsKetNoi = ["Bluetooth", "Có dây"];

$id = (int) ($_GET["sua"] ?? 0);
$ten = "";
$hangId = 0;
$kieu = "";
$ketNoi = "";
$gia = 0;
$soLuong = 0;
$moTa = "";
$loi = "";

$hanhDong = $_POST["hanhdong"] ?? "";

if ($hanhDong === "xoa") {
    $idXoa = (int) ($_POST["id"] ?? 0);
    $stmt = mysqli_prepare($conn, "DELETE FROM tainghe WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $idXoa);
    mysqli_stmt_execute($stmt);
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
} elseif ($hanhDong === "luu") {
    $id = (int) ($_POST["id"] ?? 0);
    $ten = trim($_POST["ten"] ?? "");
    $hangId = (int) ($_POST["hang_id"] ?? 0);
    $kieu = $_POST["kieu"] ?? "";
    $ketNoi = $_POST["ket_noi"] ?? "";
    $gia = (int) ($_POST["gia"] ?? 0);
    $soLuong = (int) ($_POST["so_luong"] ?? 0);
    $moTa = trim($_POST["mo_ta"] ?? "");

    if ($ten === "") {
        $loi = "Tên tai nghe không được để trống";
    } elseif ($hangId === 0) {
        $loi = "Hãy chọn hãng";
    } elseif (!in_array($kieu, $dsKieu)) {
        $loi = "Hãy chọn kiểu tai nghe";
    } elseif (!in_array($ketNoi, $dsKetNoi)) {
        $loi = "Hãy chọn kiểu kết nối";
    } elseif ($gia < 0 || $soLuong < 0) {
        $loi = "Giá và số lượng không được âm";
    } else {
        if ($id === 0) {
            $stmt = mysqli_prepare($conn, "INSERT INTO tainghe (ten, hang_id, kieu, ket_noi, gia, so_luong, mo_ta) VALUES (?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sissiis", $ten, $hangId, $kieu, $ketNoi, $gia, $soLuong, $moTa);
        } else {
            $stmt = mysqli_prepare($conn, "UPDATE tainghe SET ten = ?, hang_id = ?, kieu = ?, ket_noi = ?, gia = ?, so_luong = ?, mo_ta = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "sissiisi", $ten, $hangId, $kieu, $ketNoi, $gia, $soLuong, $moTa, $id);
        }
        mysqli_stmt_execute($stmt);
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    }
} elseif ($id > 0) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM tainghe WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $tn = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    if ($tn) {
        $ten = $tn["ten"];
        $hangId = $tn["hang_id"];
        $kieu = $tn["kieu"];
        $ketNoi = $tn["ket_noi"];
        $gia = (int) $tn["gia"];
        $soLuong = $tn["so_luong"];
        $moTa = $tn["mo_ta"];
    } else {
        $id = 0;
    }
}

$dsHang = mysqli_query($conn, "SELECT * FROM hang");
$dsTn = mysqli_query($conn, "SELECT * FROM v_tainghe ORDER BY id DESC");
?>
<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý tai nghe</title>
    <style>
        body {
  font-family: Arial, sans-serif;
  background: #f4f6f8;
  color: #333;
  max-width: 1000px;
  margin: 30px auto;
  padding: 0 16px;
}

h2 {
  text-align: center;
  color: #1e3a5f;
}

.khung {
  background: #fff;
  border: 1px solid #dde3ea;
  border-radius: 8px;
  padding: 16px 20px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
}

.khung h3 {
  margin-top: 0;
  color: #1e3a5f;
}

label {
  display: block;
  margin-top: 12px;
  font-size: 14px;
  font-weight: bold;
}

input[type="text"],
input[type="number"],
select {
  width: 100%;
  padding: 9px;
  border: 1px solid #ccd3db;
  border-radius: 5px;
  box-sizing: border-box;
  font-size: 15px;
}

input[type="text"]:focus,
input[type="number"]:focus,
select:focus {
  outline: none;
  border-color: #2f80ed;
  box-shadow: 0 0 0 3px rgba(47, 128, 237, 0.2);
}

button {
  padding: 7px 14px;
  border: none;
  border-radius: 5px;
  font-size: 14px;
  cursor: pointer;
}

.khung button {
  margin-top: 16px;
  background: #2f80ed;
  color: #fff;
}

.khung button:hover {
  background: #1c66c8;
}

.khung a {
  margin-left: 10px;
  color: #666;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  background: #fff;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
}

th {
  background: #1e3a5f;
  color: #fff;
}

th,
td {
  padding: 10px;
  text-align: left;
  border-bottom: 1px solid #e6ebf0;
}

tr:nth-child(even) td {
  background: #f9fbfd;
}

tr:hover td {
  background: #eef4fd;
}

td form {
  display: inline;
}

td a {
  display: inline-block;
  padding: 5px 12px;
  background: #27ae60;
  color: #fff;
  text-decoration: none;
  border-radius: 5px;
  font-size: 14px;
}

td a:hover {
  background: #1e8e4e;
}

td form button {
  margin-top: 0;
  padding: 5px 12px;
  background: #e74c3c;
  color: #fff;
}

td form button:hover {
  background: #c0392b;
}

.loi {
  color: #c0392b;
  background: #fdecea;
  padding: 8px 12px;
  border-radius: 5px;
}
    </style>
  </head>
  <body>
    <h2>Quản lý tai nghe</h2>

    <div class="khung">
      <h3><?= $id === 0 ? "Thêm tai nghe" : "Sửa tai nghe #" . $id ?></h3>

      <?php if ($loi !== "") { ?>
        <p class="loi"><?= $loi ?></p>
      <?php } ?>

      <form method="post">
        <input type="hidden" name="hanhdong" value="luu" />
        <input type="hidden" name="id" value="<?= $id ?>" />

        <label>Tên tai nghe</label>
        <input type="text" name="ten" value="<?= htmlspecialchars($ten) ?>" />

        <label>Hãng</label>
        <select name="hang_id">
          <option value="0">-- Chọn hãng --</option>
          <?php while ($h = mysqli_fetch_assoc($dsHang)) { ?>
            <option value="<?= $h["id"] ?>" <?= $h["id"] == $hangId ? "selected" : "" ?>>
              <?= htmlspecialchars($h["ten_hang"]) ?>
            </option>
          <?php } ?>
        </select>

        <label>Kiểu</label>
        <select name="kieu">
          <option value="">-- Chọn kiểu --</option>
          <?php foreach ($dsKieu as $k) { ?>
            <option value="<?= $k ?>" <?= $k === $kieu ? "selected" : "" ?>><?= $k ?></option>
          <?php } ?>
        </select>

        <label>Kết nối</label>
        <select name="ket_noi">
          <option value="">-- Chọn kết nối --</option>
          <?php foreach ($dsKetNoi as $k) { ?>
            <option value="<?= $k ?>" <?= $k === $ketNoi ? "selected" : "" ?>><?= $k ?></option>
          <?php } ?>
        </select>

        <label>Giá (đồng)</label>
        <input type="number" name="gia" value="<?= $gia ?>" />

        <label>Số lượng</label>
        <input type="number" name="so_luong" value="<?= $soLuong ?>" />

        <label>Mô tả</label>
        <input type="text" name="mo_ta" value="<?= htmlspecialchars($moTa) ?>" />

        <button type="submit"><?= $id === 0 ? "Thêm" : "Cập nhật" ?></button>
        <?php if ($id !== 0) { ?>
          <a href="<?= $_SERVER["PHP_SELF"] ?>">Hủy</a>
        <?php } ?>
      </form>
    </div>

    <table>
      <tr>
        <th>ID</th>
        <th>Tên</th>
        <th>Hãng</th>
        <th>Kiểu</th>
        <th>Kết nối</th>
        <th>Giá</th>
        <th>Số lượng</th>
        <th>Thao tác</th>
      </tr>

      <?php if (mysqli_num_rows($dsTn) === 0) { ?>
        <tr>
          <td colspan="8">Chưa có tai nghe nào</td>
        </tr>
      <?php } ?>

      <?php while ($t = mysqli_fetch_assoc($dsTn)) { ?>
        <tr>
          <td><?= $t["id"] ?></td>
          <td><?= htmlspecialchars($t["ten"]) ?></td>
          <td><?= htmlspecialchars($t["ten_hang"]) ?></td>
          <td><?= htmlspecialchars($t["kieu"]) ?></td>
          <td><?= htmlspecialchars($t["ket_noi"]) ?></td>
          <td><?= number_format($t["gia"], 0, ",", ".") ?> đ</td>
          <td><?= $t["so_luong"] ?></td>
          <td>
            <a href="?sua=<?= $t["id"] ?>">Sửa</a>
            <form method="post" onsubmit="return confirm('Xóa tai nghe này?')">
              <input type="hidden" name="hanhdong" value="xoa" />
              <input type="hidden" name="id" value="<?= $t["id"] ?>" />
              <button type="submit">Xóa</button>
            </form>
          </td>
        </tr>
      <?php } ?>
    </table>
  </body>
</html>