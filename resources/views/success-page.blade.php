<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Booking Berhasil</title>
  <style>
    body,
    html {
      height: 100%;
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .card {
      background-color: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      text-align: center;
      max-width: 400px;
      width: 90%;
    }

    .card h2 {
      color: #28a745;
      margin-bottom: 20px;
    }

    .card p {
      margin-bottom: 25px;
      color: #333;
    }

    .whatsapp-link {
      display: inline-block;
      background-color: #25d366;
      color: white;
      padding: 10px 20px;
      border-radius: 50px;
      text-decoration: none;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    .whatsapp-link:hover {
      background-color: #1ebe5d;
    }

    .whatsapp-link i {
      margin-right: 8px;
    }
  </style>
  <!-- Link Remixicon -->
  <link rel="stylesheet" href="{{asset('vendor/RemixIcon-master/fonts/remixicon.css')}}" />

</head>

<body>
  <div class="card">
    <h2>Transaksi Berhasil!</h2>
    <p>
      Booking kelas Anda berhasil. Silakan hubungi admin untuk mendapatkan
      kode booking.
    </p>
    <a class="whatsapp-link" href="https://wa.me/6285190331512" target="_blank">
      <i class="ri-whatsapp-line"></i> Hubungi Admin
    </a>
  </div>
</body>

</html>