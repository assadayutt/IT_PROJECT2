const express = require('express');
const app = express();
const fetch = require('node-fetch');
const bodyParser = require('body-parser'); // เพิ่ม middleware bodyParser

// Middleware สำหรับอนุญาตการใช้งาน CORS
app.use((req, res, next) => {
    res.header('Access-Control-Allow-Origin', '*'); // อนุญาตให้เรียกใช้งานจากทุกโดเมน
    res.header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
    next();
});

// เพิ่ม middleware bodyParser เพื่ออ่านข้อมูลจาก request body
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

// Endpoint สำหรับส่งข้อมูล Line Notify
app.post('/send-line-notify', async (req, res) => {
    try {
        const lineToken = req.body.lineToken; // รับ token จาก request body
        const message = req.body.message; // รับ message จาก request body
        const lineApiUrl = 'https://notify-api.line.me/api/notify'; // เพิ่ม URL ของ Line API

        const headers = {
            'Authorization': `Bearer ${lineToken}`,
            'Content-Type': 'application/x-www-form-urlencoded',
        };

        const formData = new URLSearchParams(); // แปลงข้อมูลเป็น URL-encoded
        formData.append('message', message);

        const response = await fetch(lineApiUrl, {
            method: 'POST',
            headers: headers,
            body: formData.toString(), // แปลง formData เป็นสตริง
        });

        if (response.ok) {
            res.status(200).json({ success: true, message: 'ส่งข้อความ Line Notify สำเร็จ' });
        } else {
            res.status(500).json({ success: false, message: 'เกิดข้อผิดพลาดในการส่งข้อความ Line Notify' });
        }
    } catch (error) {
        console.error(error);
        res.status(500).json({ success: false, message: 'เกิดข้อผิดพลาดในการส่งข้อความ Line Notify' });
    }
});

const PORT = process.env.PORT || 3001;
app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
});
 