"use client";

import React, { useState } from "react";
import { useParams, useRouter } from "next/navigation";
import { Navbar } from "@/components/layout/Navbar";
import { Footer } from "@/components/layout/Footer";
import { useKosStore } from "@/lib/store";
import {
  formatRupiah,
  getRoomStatusMeta,
  buildWhatsAppLink,
} from "@/lib/formatters";
import {
  MapPin,
  Building2,
  Check,
  ShieldCheck,
  MessageCircle,
  Calendar,
  Layers,
  Sparkles,
  ArrowLeft,
  X,
  Send,
  Phone,
  Bed,
  CheckCircle2,
} from "lucide-react";
import Link from "next/link";

export default function KosDetailPage() {
  const params = useParams();
  const router = useRouter();
  const { properties, rooms } = useKosStore();
  const [selectedFloor, setSelectedFloor] = useState<number | "ALL">("ALL");
  const [activePhotoIdx, setActivePhotoIdx] = useState(0);

  // Booking Modal State
  const [isBookingOpen, setIsBookingOpen] = useState(false);
  const [selectedRoomId, setSelectedRoomId] = useState<string>("");
  const [applicantName, setApplicantName] = useState("");
  const [applicantPhone, setApplicantPhone] = useState("");
  const [applicantJob, setApplicantJob] = useState("");
  const [applicantStartDate, setApplicantStartDate] = useState("2026-07-01");
  const [applicantPeriod, setApplicantPeriod] = useState("1 Bulan");
  const [bookingSuccess, setBookingSuccess] = useState(false);

  const propId = params.id as string;
  const property = properties.find((p) => p.id === propId) || properties[0];
  const propRooms = rooms.filter((r) => r.propertyId === property.id);

  const displayedRooms = propRooms.filter(
    (r) => selectedFloor === "ALL" || r.floor === selectedFloor
  );

  const availableCount = propRooms.filter((r) => r.status === "TERSEDIA").length;

  const handleOpenBooking = (roomId?: string) => {
    if (roomId) setSelectedRoomId(roomId);
    else {
      const firstAvailable = propRooms.find((r) => r.status === "TERSEDIA");
      if (firstAvailable) setSelectedRoomId(firstAvailable.id);
    }
    setIsBookingOpen(true);
  };

  const handleBookingSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setBookingSuccess(true);
    setTimeout(() => {
      setBookingSuccess(false);
      setIsBookingOpen(false);
      alert(
        `Pengajuan sewa atas nama ${applicantName} berhasil dikirimkan ke pengelola (${property.managerName}). Tim kami akan menghubungi via WhatsApp ${applicantPhone}.`
      );
    }, 1500);
  };

  const selectedRoomObj = propRooms.find((r) => r.id === selectedRoomId);

  const waLink = buildWhatsAppLink(
    property.managerPhone,
    `Halo ${property.managerName}, saya tertarik untuk survei & sewa kamar di ${property.name}. Apakah masih tersedia?`
  );

  return (
    <>
      <Navbar />

      <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 space-y-8">
        {/* Back Link */}
        <div>
          <Link
            href="/"
            className="inline-flex items-center space-x-1.5 text-xs font-semibold text-slate-500 hover:text-orange-600 transition"
          >
            <ArrowLeft className="w-4 h-4" />
            <span>Kembali ke Semua Kos</span>
          </Link>
        </div>

        {/* Header & Badges */}
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div className="space-y-2">
            <div className="flex items-center space-x-2">
              <span className="px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-800 border border-orange-200">
                Kos {property.type}
              </span>
              <span
                className={`px-3 py-1 rounded-full text-xs font-bold ${
                  availableCount > 0
                    ? "bg-emerald-100 text-emerald-800 border border-emerald-200"
                    : "bg-rose-100 text-rose-800 border border-rose-200"
                }`}
              >
                {availableCount > 0
                  ? `Tersedia ${availableCount} Kamar Kosong`
                  : "Kamar Penuh"}
              </span>
            </div>
            <h1 className="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
              {property.name}
            </h1>
            <p className="text-xs sm:text-sm text-slate-600 flex items-center space-x-1.5">
              <MapPin className="w-4 h-4 text-orange-500 flex-shrink-0" />
              <span>
                {property.address}, {property.city}, {property.province} {property.postalCode}
              </span>
            </p>
          </div>

          {/* Quick CTA */}
          <div className="flex items-center space-x-3">
            <a
              href={waLink}
              target="_blank"
              rel="noopener noreferrer"
              className="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm flex items-center space-x-2"
            >
              <MessageCircle className="w-4 h-4" />
              <span>Chat WhatsApp</span>
            </a>
            <button
              onClick={() => handleOpenBooking()}
              className="px-5 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold transition shadow-md flex items-center space-x-2"
            >
              <Calendar className="w-4 h-4" />
              <span>Ajukan Sewa Kamar</span>
            </button>
          </div>
        </div>

        {/* Gallery */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
          <div className="lg:col-span-2 relative h-80 sm:h-96 rounded-2xl overflow-hidden shadow-sm">
            <img
              src={property.images[activePhotoIdx] || property.coverImage}
              alt={property.name}
              className="w-full h-full object-cover"
            />
          </div>
          <div className="grid grid-cols-2 lg:grid-cols-1 gap-3">
            {property.images.slice(0, 3).map((img, idx) => (
              <div
                key={idx}
                onClick={() => setActivePhotoIdx(idx)}
                className={`cursor-pointer relative h-28 sm:h-30 rounded-xl overflow-hidden border-2 transition ${
                  activePhotoIdx === idx ? "border-orange-500 shadow-md scale-98" : "border-transparent opacity-80 hover:opacity-100"
                }`}
              >
                <img src={img} alt="Foto kamar" className="w-full h-full object-cover" />
              </div>
            ))}
          </div>
        </div>

        {/* Content Tabs / 2 Column Info */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Left Column: Description, Facilities, Rules, Map */}
          <div className="lg:col-span-2 space-y-8">
            {/* Description */}
            <div className="bg-white rounded-2xl border border-slate-200 p-6 space-y-3">
              <h2 className="text-base font-bold text-slate-900">Tentang Properti Ini</h2>
              <p className="text-xs sm:text-sm text-slate-600 leading-relaxed">
                {property.description}
              </p>
            </div>

            {/* Facilities */}
            <div className="bg-white rounded-2xl border border-slate-200 p-6 space-y-4">
              <h2 className="text-base font-bold text-slate-900">Fasilitas Properti & Kamar</h2>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                {property.facilities.map((fac, idx) => (
                  <div key={idx} className="flex items-center space-x-2 text-xs sm:text-sm text-slate-700">
                    <div className="w-5 h-5 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center flex-shrink-0">
                      <Check className="w-3.5 h-3.5" />
                    </div>
                    <span>{fac}</span>
                  </div>
                ))}
              </div>
            </div>

            {/* Rules */}
            <div className="bg-white rounded-2xl border border-slate-200 p-6 space-y-4">
              <h2 className="text-base font-bold text-slate-900">Peraturan & Ketertiban Kos</h2>
              <ul className="space-y-2 text-xs sm:text-sm text-slate-600">
                {property.rules.map((rule, idx) => (
                  <li key={idx} className="flex items-start space-x-2">
                    <span className="w-1.5 h-1.5 rounded-full bg-orange-500 mt-2 flex-shrink-0" />
                    <span>{rule}</span>
                  </li>
                ))}
              </ul>
            </div>

            {/* Simulated Interactive Map */}
            <div className="bg-white rounded-2xl border border-slate-200 p-6 space-y-4">
              <div className="flex items-center justify-between">
                <div>
                  <h2 className="text-base font-bold text-slate-900">Lokasi & Peta Sekitar</h2>
                  <p className="text-xs text-slate-500">
                    Koordinat: {property.latitude}, {property.longitude}
                  </p>
                </div>
                <span className="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                  Strategis & Bebas Banjir
                </span>
              </div>

              {/* Simulated Map Container */}
              <div className="relative h-64 rounded-xl bg-slate-100 overflow-hidden border border-slate-300 flex items-center justify-center">
                {/* SVG Map Graphic */}
                <div className="absolute inset-0 bg-[#e5e3df] flex flex-col justify-between p-4">
                  <div className="h-6 bg-slate-300/60 rounded-full w-2/3" />
                  <div className="h-12 bg-amber-200/50 rounded-xl w-1/2 ml-auto" />
                  <div className="h-8 bg-slate-300/60 rounded-full w-4/5" />
                </div>

                {/* Pin Marker */}
                <div className="relative z-10 flex flex-col items-center animate-bounce">
                  <div className="px-3 py-1.5 rounded-xl bg-orange-600 text-white font-bold text-xs shadow-xl flex items-center space-x-1.5">
                    <Building2 className="w-3.5 h-3.5" />
                    <span>{property.name}</span>
                  </div>
                  <div className="w-3 h-3 bg-orange-600 rotate-45 -mt-1 shadow" />
                </div>

                {/* Nearby highlights */}
                <div className="absolute bottom-3 left-3 right-3 bg-white/95 backdrop-blur-sm rounded-lg p-2.5 border border-slate-200 text-[11px] text-slate-700 flex flex-wrap justify-between gap-2">
                  <span>📍 5 menit ke Pusat Perbelanjaan & Kuliner</span>
                  <span>🚗 Akses mudah Transportasi Umum & Tol</span>
                  <span>🏥 10 menit ke Rumah Sakit Terdekat</span>
                </div>
              </div>
            </div>
          </div>

          {/* Right Column: Manager card & Pricing rules */}
          <div className="space-y-6">
            {/* Manager Card */}
            <div className="bg-white rounded-2xl border border-slate-200 p-6 space-y-4 shadow-xs">
              <h3 className="text-sm font-bold text-slate-900">Pengelola Properti</h3>
              <div className="flex items-center space-x-3">
                <div className="w-12 h-12 rounded-full bg-gradient-to-tr from-orange-500 to-amber-500 flex items-center justify-center text-white font-bold text-lg">
                  {property.managerName.slice(0, 2).toUpperCase()}
                </div>
                <div>
                  <p className="text-sm font-bold text-slate-900">{property.managerName}</p>
                  <p className="text-xs text-slate-500">Manajer Operasional Properti</p>
                  <p className="text-xs text-orange-600 font-semibold">{property.managerPhone}</p>
                </div>
              </div>

              <div className="pt-2 border-t border-slate-100 space-y-2">
                <a
                  href={waLink}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center justify-center space-x-2 transition shadow-xs"
                >
                  <MessageCircle className="w-4 h-4" />
                  <span>Chat WhatsApp Pengelola</span>
                </a>
                <button
                  onClick={() => handleOpenBooking()}
                  className="w-full py-2.5 px-4 rounded-xl bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs flex items-center justify-center space-x-2 transition shadow-xs"
                >
                  <Calendar className="w-4 h-4" />
                  <span>Ajukan Booking Kamar</span>
                </button>
              </div>
            </div>

            {/* Transparency Pricing Box */}
            <div className="bg-amber-50/60 rounded-2xl border border-amber-200 p-6 space-y-3 text-xs text-amber-950">
              <div className="flex items-center space-x-2 font-bold text-sm text-amber-900">
                <ShieldCheck className="w-4 h-4 text-orange-600" />
                <span>Ketentuan Transparan Biaya:</span>
              </div>
              <ul className="space-y-1.5 text-[11px] text-amber-900/80">
                <li>• Uang Jaminan (Deposit): {formatRupiah(property.pricingRules.depositDefault)} (dikembalikan di akhir sewa).</li>
                <li>• Iuran WiFi Fiber: {formatRupiah(property.pricingRules.wifiFee)} / bulan.</li>
                <li>• Iuran Kebersihan: {formatRupiah(property.pricingRules.cleaningFee)} / bulan.</li>
                <li>• Denda Keterlambatan: {formatRupiah(property.pricingRules.lateFeePerWeek)} / minggu lewat jatuh tempo.</li>
              </ul>
            </div>
          </div>
        </div>

        {/* Section: Daftar Kamar & Status Real-time */}
        <section className="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 space-y-6">
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <h2 className="text-xl font-bold text-slate-900">Daftar Kamar & Ketersediaan</h2>
              <p className="text-xs text-slate-500">
                Total {propRooms.length} kamar ({property.floors} lantai). Status diupdate secara real-time.
              </p>
            </div>

            {/* Floor Filter */}
            <div className="flex items-center space-x-2">
              <span className="text-xs font-semibold text-slate-600">Pilih Lantai:</span>
              <div className="flex space-x-1">
                {["ALL", 1, 2].map((floor) => (
                  <button
                    key={floor}
                    onClick={() => setSelectedFloor(floor as any)}
                    className={`px-3 py-1.5 rounded-lg text-xs font-bold transition ${
                      selectedFloor === floor
                        ? "bg-orange-600 text-white"
                        : "bg-slate-100 text-slate-600 hover:bg-slate-200"
                    }`}
                  >
                    {floor === "ALL" ? "Semua" : `Lantai ${floor}`}
                  </button>
                ))}
              </div>
            </div>
          </div>

          {/* Rooms Grid */}
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            {displayedRooms.map((room) => {
              const meta = getRoomStatusMeta(room.status);
              const isAvailable = room.status === "TERSEDIA";

              return (
                <div
                  key={room.id}
                  className={`rounded-xl border p-4 flex flex-col justify-between transition ${
                    isAvailable ? "border-emerald-300 bg-emerald-50/20 hover:shadow-md" : "border-slate-200 bg-white"
                  }`}
                >
                  <div className="space-y-3">
                    <div className="flex items-center justify-between">
                      <div className="flex items-center space-x-2">
                        <span className="text-base font-extrabold text-slate-900">
                          Kamar #{room.roomNumber}
                        </span>
                        <span className="text-[10px] text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                          Lt. {room.floor}
                        </span>
                      </div>
                      <span className={`px-2 py-0.5 rounded-full text-[10px] font-bold border ${meta.bg}`}>
                        {meta.label}
                      </span>
                    </div>

                    <div className="flex items-baseline space-x-2">
                      <span className="text-lg font-black text-slate-900">
                        {formatRupiah(room.priceMonthly)}
                      </span>
                      <span className="text-[11px] text-slate-500">/ bulan</span>
                    </div>

                    <p className="text-[11px] text-slate-500">
                      Tipe: <strong>{room.type}</strong> • Ukuran: {room.size}
                    </p>

                    <div className="flex flex-wrap gap-1">
                      {room.facilities.slice(0, 3).map((f, i) => (
                        <span
                          key={i}
                          className="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px]"
                        >
                          {f}
                        </span>
                      ))}
                      {room.facilities.length > 3 && (
                        <span className="text-[10px] text-slate-400">+{room.facilities.length - 3}</span>
                      )}
                    </div>
                  </div>

                  <div className="pt-4 mt-3 border-t border-slate-100">
                    {isAvailable ? (
                      <button
                        onClick={() => handleOpenBooking(room.id)}
                        className="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition flex items-center justify-center space-x-1"
                      >
                        <Calendar className="w-3.5 h-3.5" />
                        <span>Pilih Kamar Ini</span>
                      </button>
                    ) : (
                      <button
                        disabled
                        className="w-full py-2 bg-slate-100 text-slate-400 rounded-lg text-xs font-medium cursor-not-allowed"
                      >
                        Tidak Tersedia ({meta.label})
                      </button>
                    )}
                  </div>
                </div>
              );
            })}
          </div>
        </section>
      </main>

      {/* Booking Form Modal */}
      {isBookingOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4">
          <div className="relative w-full max-w-lg rounded-2xl bg-white shadow-2xl overflow-hidden border border-slate-200 animate-in fade-in zoom-in-95 duration-150">
            <div className="bg-gradient-to-r from-orange-600 to-amber-600 px-6 py-4 text-white flex items-center justify-between">
              <div className="flex items-center space-x-2">
                <Calendar className="w-5 h-5 text-amber-200" />
                <h3 className="font-bold text-base">Formulir Pengajuan Sewa Kamar</h3>
              </div>
              <button
                onClick={() => setIsBookingOpen(false)}
                className="text-white/80 hover:text-white"
              >
                <X className="w-5 h-5" />
              </button>
            </div>

            {bookingSuccess ? (
              <div className="p-8 text-center space-y-3">
                <div className="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 mx-auto flex items-center justify-center">
                  <CheckCircle2 className="w-8 h-8" />
                </div>
                <h4 className="text-base font-bold text-slate-900">Pengajuan Berhasil Terkirim!</h4>
                <p className="text-xs text-slate-600">
                  Pengelola sedang memverifikasi formulir Anda dan akan menghubungi via WhatsApp.
                </p>
              </div>
            ) : (
              <form onSubmit={handleBookingSubmit} className="p-6 space-y-4 text-xs">
                {/* Chosen room info */}
                <div className="p-3 bg-orange-50 rounded-xl border border-orange-200 text-orange-950 flex items-center justify-between">
                  <div>
                    <p className="font-bold">
                      {property.name} — Kamar #{selectedRoomObj?.roomNumber || "104"}
                    </p>
                    <p className="text-[11px] text-orange-800">
                      Tipe {selectedRoomObj?.type || "Standard"} • {formatRupiah(selectedRoomObj?.priceMonthly || 1950000)} / bln
                    </p>
                  </div>
                  <span className="px-2 py-1 rounded bg-white text-orange-700 font-bold text-[10px]">
                    Siap Huni
                  </span>
                </div>

                <div className="space-y-1">
                  <label className="font-semibold text-slate-700">Nama Lengkap Sesuai KTP *</label>
                  <input
                    type="text"
                    required
                    placeholder="Contoh: Muhammad Ilham"
                    value={applicantName}
                    onChange={(e) => setApplicantName(e.target.value)}
                    className="w-full px-3 py-2 border rounded-lg outline-none focus:border-orange-500 text-slate-800 text-xs"
                  />
                </div>

                <div className="grid grid-cols-2 gap-3">
                  <div className="space-y-1">
                    <label className="font-semibold text-slate-700">Nomor WhatsApp *</label>
                    <input
                      type="tel"
                      required
                      placeholder="0812xxxxxxxx"
                      value={applicantPhone}
                      onChange={(e) => setApplicantPhone(e.target.value)}
                      className="w-full px-3 py-2 border rounded-lg outline-none focus:border-orange-500 text-slate-800 text-xs"
                    />
                  </div>

                  <div className="space-y-1">
                    <label className="font-semibold text-slate-700">Pekerjaan / Kuliah *</label>
                    <input
                      type="text"
                      required
                      placeholder="Mahasiswa / Karyawan"
                      value={applicantJob}
                      onChange={(e) => setApplicantJob(e.target.value)}
                      className="w-full px-3 py-2 border rounded-lg outline-none focus:border-orange-500 text-slate-800 text-xs"
                    />
                  </div>
                </div>

                <div className="grid grid-cols-2 gap-3">
                  <div className="space-y-1">
                    <label className="font-semibold text-slate-700">Rencana Tanggal Masuk *</label>
                    <input
                      type="date"
                      required
                      value={applicantStartDate}
                      onChange={(e) => setApplicantStartDate(e.target.value)}
                      className="w-full px-3 py-2 border rounded-lg outline-none focus:border-orange-500 text-slate-800 text-xs"
                    />
                  </div>

                  <div className="space-y-1">
                    <label className="font-semibold text-slate-700">Durasi Sewa *</label>
                    <select
                      value={applicantPeriod}
                      onChange={(e) => setApplicantPeriod(e.target.value)}
                      className="w-full px-3 py-2 border rounded-lg outline-none focus:border-orange-500 text-slate-800 text-xs bg-white"
                    >
                      <option value="1 Bulan">1 Bulan (Bulanan)</option>
                      <option value="3 Bulan">3 Bulan</option>
                      <option value="6 Bulan">6 Bulan</option>
                      <option value="1 Tahun">1 Tahun (Diskon 5%)</option>
                    </select>
                  </div>
                </div>

                <div className="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                  <button
                    type="button"
                    onClick={() => setIsBookingOpen(false)}
                    className="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-medium transition"
                  >
                    Batal
                  </button>
                  <button
                    type="submit"
                    className="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-lg transition shadow-sm flex items-center space-x-1.5"
                  >
                    <Send className="w-3.5 h-3.5" />
                    <span>Kirim Pengajuan Sewa</span>
                  </button>
                </div>
              </form>
            )}
          </div>
        </div>
      )}

      <Footer />
    </>
  );
}
