#include <SPI.h>
#include <MFRC522.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <WiFi.h>
#include <HTTPClient.h>

// ---------- WIFI CONFIG ----------
const char* ssid = "Backup";               // GANTI DENGAN NAMA WIFI
const char* password = "05062004";         // GANTI DENGAN PASSWORD WIFI
String serverBaseUrl = "http://10.128.85.159:8000/input-rfid"; 

// ---------- PIN CONFIG ----------
#define SS_PIN       5     // RFID SDA
#define RST_PIN      0     // RFID RST
#define FAN_PIN      14    // IN1: Kipas (Nyala 30s, Mati 30s)
#define SELENOID_PIN 26    // IN2: Pintu/Selenoid (Nyala saat absen sukses)
#define BUZZER_PIN   4     // Buzzer

// ---------- OBJECT ----------
MFRC522 mfrc522(SS_PIN, RST_PIN);
LiquidCrystal_I2C lcd(0x27, 16, 2);   

// ---------- CONSTANTS & VARS ----------
const bool RELAY_ACTIVE_LOW = true; // Board Relay umum (IN1/IN2) biasanya Active LOW

unsigned long lastFanToggle = 0;
bool fanState = false; // Status Kipas saat ini

// Helper untuk menyalakan/mematikan alat sesuai logika Active Low
void setDevice(int pin, bool turnOn) {
  if (RELAY_ACTIVE_LOW) {
    digitalWrite(pin, turnOn ? LOW : HIGH); // Low = Nyala
  } else {
    digitalWrite(pin, turnOn ? HIGH : LOW); // High = Nyala
  }
}

// Fungsi Bunyi Buzzer
void beep(int count = 1) {
  for (int i=0; i<count; i++) {
    digitalWrite(BUZZER_PIN, HIGH);
    delay(100);
    digitalWrite(BUZZER_PIN, LOW);
    delay(100);
  }
}

void showLCD(String l1, String l2=""){
  lcd.clear(); 
  lcd.setCursor(0,0); lcd.print(l1);
  lcd.setCursor(0,1); lcd.print(l2);
}

void setup() {
  Serial.begin(115200);

  // Init Pins
  pinMode(FAN_PIN, OUTPUT);
  pinMode(SELENOID_PIN, OUTPUT);
  pinMode(BUZZER_PIN, OUTPUT);
  
  // Matikan semua dulu
  setDevice(FAN_PIN, false);
  setDevice(SELENOID_PIN, false);
  digitalWrite(BUZZER_PIN, LOW);

  // Init LCD
  lcd.init(); 
  lcd.backlight();
  showLCD("Booting...", "System Start");
  beep(1);

  // Init WiFi
  WiFi.begin(ssid, password);
  int cursor = 0;
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
    lcd.setCursor(cursor++, 1); lcd.print(".");
    if(cursor > 15) cursor = 0;
  }
  
  Serial.println("\nWiFi Connected");
  showLCD("WiFi OK", WiFi.localIP().toString());
  beep(2);
  delay(1500);

  // Init RFID
  SPI.begin();
  mfrc522.PCD_Init();
  
  showLCD("Sistem Absensi", "Tempel Kartu...");
}

void loop() {
  // --- LOGIKA KIPAS (FAN) ---
  // Nyala 30 detik, Mati 30 detik (Non-blocking)
  unsigned long currentMillis = millis();
  if (currentMillis - lastFanToggle >= 30000) { // 30000 ms = 30 detik
    lastFanToggle = currentMillis;
    fanState = !fanState; // Tukar status (Nyala <-> Mati)
    setDevice(FAN_PIN, fanState);
    
    // Debug info ke serial (opsional)
    Serial.print("Fan Status: "); Serial.println(fanState ? "ON" : "OFF");
  }


  // --- LOGIKA ABSENSI (RFID) ---
  // Cek apakah ada kartu baru
  if (!mfrc522.PICC_IsNewCardPresent()) return;
  if (!mfrc522.PICC_ReadCardSerial()) return;

  // Baca UID
  String uidString = "";
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    if (mfrc522.uid.uidByte[i] < 0x10) uidString += "0";
    uidString += String(mfrc522.uid.uidByte[i], HEX);
  }
  uidString.toUpperCase();

  Serial.print("UID: "); Serial.println(uidString);
  showLCD("Memproses...", uidString);
  beep(1);

  // Kirim ke Server
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    String url = serverBaseUrl + "?uid=" + uidString;
    http.begin(url);
    
    int httpCode = http.GET();
    if (httpCode > 0) {
      String payload = http.getString();
      Serial.println("Resp: " + payload);
      
      if (httpCode == 200) {
        if (payload.indexOf("SUCCESS") > 0) {
             showLCD("Hadir!", "Pintu Terbuka");
             
             // Buka Selenoid (Pintu)
             setDevice(SELENOID_PIN, true); 
             beep(1);
             delay(3000); // Tahan pintu 3 detik
             setDevice(SELENOID_PIN, false);
             
        } else if (payload.indexOf("ALREADY") > 0) {
             showLCD("Sdh Hadir", "Pintu Terbuka");
             setDevice(SELENOID_PIN, true);
             beep(2);
             delay(2000); // Tahan pintu 2 detik
             setDevice(SELENOID_PIN, false);
        } else {
             showLCD("Sukses", "Data Masuk");
             beep(1);
             delay(2000);
        }
      } else {
        // Error handling
        beep(3); 
        if (payload.indexOf("Tidak Dikenal") > 0) showLCD("Gagal!", "Unregistered");
        else if (payload.indexOf("Tidak Ada Jadwal") > 0) showLCD("Gagal!", "No Schedule");
        else showLCD("Error HTTP", String(httpCode));
        delay(2000);
      }
    } else {
      showLCD("Conn Error", "Cek Server");
      beep(3);
      delay(2000);
    }
    http.end();
  } else {
    showLCD("WiFi Putus", "Reconnect...");
    WiFi.reconnect();
    delay(1000);
  }

  // Reset RFID
  mfrc522.PICC_HaltA();
  mfrc522.PCD_StopCrypto1();
  
  // Kembalikan tulisan LCD (Kecuali jika sedang delay pintu, di atas sudah handle delay)
  showLCD("Sistem Absensi", "Tempel Kartu...");
}
