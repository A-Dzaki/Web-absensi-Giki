void setup() {
  pinMode(15, OUTPUT); // Cek kabel Relay IN ke Pin D15
}

void loop() {
  digitalWrite(15, HIGH); // Coba nyalakan (Active High)
  delay(1000);
  digitalWrite(15, LOW);  // Coba nyalakan (Active Low)
  delay(1000);
}
