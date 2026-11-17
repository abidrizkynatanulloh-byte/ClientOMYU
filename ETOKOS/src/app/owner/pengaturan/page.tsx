"use client";

import React, { useState } from "react";
import { useKosStore } from "@/lib/store";
import { formatRupiah } from "@/lib/formatters";
import {
  Settings,
  Building2,
  Receipt,
  MessageCircle,
  Bell,
  CheckCircle2,
  Save,
} from "lucide-react";

export default function OwnerPengaturanPage() {
  const { properties, addAnnouncement } = useKosStore();
  const [selectedPropIdx, setSelectedPropIdx] = useState(0);
  const prop = properties[selectedPropIdx];

  const [lateFee, setLateFee] = useState(prop.pricingRules.lateFeePerWeek);
  const [wifiFee, setWifiFee] = useState(prop.pricingRules.wifiFee);
  const [cleanFee, setCleanFee] = useState(prop.pricingRules.cleaningFee);
  const [depositDefault, setDepositDefault] = useState(prop.pricingRules.depositDefault);

  const [waTemplate, setWaTemplate] = useState(
    "Halo Kak *{nama_penghuni}* (Kamar #{nomor_kamar}),\n\nKami mengingatkan tagihan sewa kos periode *{periode}* sebesar *{total_tagihan}* jatuh tempo pada *{tanggal_tempo}*.\n\nPembayaran dapat dilakukan via Transfer BCA 8830192841 a.n PT OMYU KOS JAYA atau QRIS di Portal Penghuni. Terima kasih! 🙏"
  );

  const [annTitle, setAnnTitle] = useState("");
  const [annContent, setAnnContent] = useState("");
  const [savedNotice, setSavedNotice] = useState("");

  const handleSaveSettings = (e: React.FormEvent) => {
    e.preventDefault();
    setSavedNotice("Pengaturan tarif, denda, dan template pengingat berhasil disimpan!");
    setTimeout(() => setSavedNotice(""), 3500);
  };

  const handlePublishAnnouncement = (e: React.FormEvent) => {
    e.preventDefault();
    if (!annTitle.trim() || !annContent.trim()) return;
    addAnnouncement({
      propertyId: "ALL",
      title: annTitle,
      content: annContent,
      category: "INFO",
      date: "2026-06-07",
      author: "Manajemen etoKos",
    });
    setAnnTitle("");
    setAnnContent("");
    setSavedNotice("Pengumuman baru berhasil diterbitkan ke seluruh Portal Penghuni!");
    setTimeout(() => setSavedNotice(""), 3500);
  };

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-extrabold text-slate-900 tracking-tight">
          Pengaturan Properti, Tarif & Template
        </h1>
        <p className="text-xs text-slate-500 mt-0.5">
          Konfigurasi profil kos, komponen tarif bulanan, aturan denda otomatis, dan template WhatsApp.
        </p>
      </div>

      {savedNotice && (
        <div className="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center space-x-2">
          <CheckCircle2 className="w-4 h-4 text-emerald-600" />
          <span>{savedNotice}</span>
        </div>
      )}

      {/* Property Selector Tabs */}
      <div className="flex space-x-2">
        {properties.map((p, idx) => (
          <button
            key={p.id}
            onClick={() => {
              setSelectedPropIdx(idx);
              setLateFee(p.pricingRules.lateFeePerWeek);
              setWifiFee(p.pricingRules.wifiFee);
              setCleanFee(p.pricingRules.cleaningFee);
              setDepositDefault(p.pricingRules.depositDefault);
            }}
            className={`px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 ${
              selectedPropIdx === idx
                ? "bg-orange-600 text-white shadow-xs"
                : "bg-white text-slate-700 border border-slate-200 hover:bg-slate-50"
            }`}
          >
            <Building2 className="w-4 h-4" />
            <span>{p.name}</span>
          </button>
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Form Profil & Tarif */}
        <form
          onSubmit={handleSaveSettings}
          className="bg-white rounded-2xl border border-slate-200 p-6 space-y-5 shadow-2xs text-xs"
        >
          <div className="flex items-center space-x-2 border-b border-slate-100 pb-3">
            <Settings className="w-4 h-4 text-orange-600" />
            <h2 className="text-sm font-bold text-slate-900">
              Profil & Aturan Tarif — {prop.name}
            </h2>
          </div>

          <div className="grid grid-cols-2 gap-3">
            <div className="space-y-1">
              <label className="font-bold text-slate-700">Nama Properti Kos</label>
              <input
                type="text"
                defaultValue={prop.name}
                className="w-full px-3 py-2 border rounded-lg bg-slate-50"
              />
            </div>
            <div className="space-y-1">
              <label className="font-bold text-slate-700">Kategori Kos</label>
              <input
                type="text"
                defaultValue={`Kos ${prop.type}`}
                className="w-full px-3 py-2 border rounded-lg bg-slate-50"
              />
            </div>
          </div>

          <div className="space-y-1">
            <label className="font-bold text-slate-700">Alamat Lengkap</label>
            <input
              type="text"
              defaultValue={`${prop.address}, ${prop.city}, ${prop.province}`}
              className="w-full px-3 py-2 border rounded-lg bg-slate-50"
            />
          </div>

          <div className="grid grid-cols-2 gap-3">
            <div className="space-y-1">
              <label className="font-bold text-slate-700">Nama Pengelola</label>
              <input
                type="text"
                defaultValue={prop.managerName}
                className="w-full px-3 py-2 border rounded-lg"
              />
            </div>
            <div className="space-y-1">
              <label className="font-bold text-slate-700">No. WhatsApp Pengelola</label>
              <input
                type="text"
                defaultValue={prop.managerPhone}
                className="w-full px-3 py-2 border rounded-lg"
              />
            </div>
          </div>

          <div className="pt-2 border-t border-slate-100">
            <p className="font-bold text-slate-900 mb-3">Komponen Tarif & Denda Otomatis:</p>
            <div className="grid grid-cols-2 gap-3">
              <div className="space-y-1">
                <label className="font-semibold text-slate-600">
                  Denda Keterlambatan / Minggu (Rp)
                </label>
                <input
                  type="number"
                  value={lateFee}
                  onChange={(e) => setLateFee(Number(e.target.value))}
                  className="w-full px-3 py-2 border rounded-lg font-bold text-rose-600"
                />
              </div>
              <div className="space-y-1">
                <label className="font-semibold text-slate-600">
                  Standar Uang Deposit (Rp)
                </label>
                <input
                  type="number"
                  value={depositDefault}
                  onChange={(e) => setDepositDefault(Number(e.target.value))}
                  className="w-full px-3 py-2 border rounded-lg font-bold text-emerald-700"
                />
              </div>
              <div className="space-y-1">
                <label className="font-semibold text-slate-600">Iuran WiFi Bulanan (Rp)</label>
                <input
                  type="number"
                  value={wifiFee}
                  onChange={(e) => setWifiFee(Number(e.target.value))}
                  className="w-full px-3 py-2 border rounded-lg"
                />
              </div>
              <div className="space-y-1">
                <label className="font-semibold text-slate-600">
                  Iuran Kebersihan & Sampah (Rp)
                </label>
                <input
                  type="number"
                  value={cleanFee}
                  onChange={(e) => setCleanFee(Number(e.target.value))}
                  className="w-full px-3 py-2 border rounded-lg"
                />
              </div>
            </div>
          </div>

          <div className="space-y-1 pt-2 border-t border-slate-100">
            <label className="font-bold text-slate-700 flex items-center space-x-1.5">
              <MessageCircle className="w-3.5 h-3.5 text-emerald-600" />
              <span>Template Pesan Pengingat WhatsApp</span>
            </label>
            <textarea
              rows={4}
              value={waTemplate}
              onChange={(e) => setWaTemplate(e.target.value)}
              className="w-full px-3 py-2 border rounded-lg text-slate-700"
            />
          </div>

          <button
            type="submit"
            className="w-full py-2.5 px-4 rounded-xl bg-orange-600 hover:bg-orange-700 text-white font-bold flex items-center justify-center space-x-2 transition shadow-xs"
          >
            <Save className="w-4 h-4" />
            <span>Simpan Konfigurasi Kos</span>
          </button>
        </form>

        {/* Buat Pengumuman Penghuni */}
        <div className="space-y-6">
          <form
            onSubmit={handlePublishAnnouncement}
            className="bg-white rounded-2xl border border-slate-200 p-6 space-y-4 shadow-2xs text-xs"
          >
            <div className="flex items-center space-x-2 border-b border-slate-100 pb-3">
              <Bell className="w-4 h-4 text-orange-600" />
              <h2 className="text-sm font-bold text-slate-900">
                Kirim Pengumuman ke Portal Penghuni
              </h2>
            </div>

            <div className="space-y-1">
              <label className="font-bold text-slate-700">Judul Pengumuman *</label>
              <input
                type="text"
                required
                placeholder="Contoh: Jadwal Fogging & Kebersihan Bersama"
                value={annTitle}
                onChange={(e) => setAnnTitle(e.target.value)}
                className="w-full px-3 py-2 border rounded-lg"
              />
            </div>

            <div className="space-y-1">
              <label className="font-bold text-slate-700">Isi Pengumuman *</label>
              <textarea
                rows={4}
                required
                placeholder="Tulis informasi penting untuk seluruh penghuni kos..."
                value={annContent}
                onChange={(e) => setAnnContent(e.target.value)}
                className="w-full px-3 py-2 border rounded-lg"
              />
            </div>

            <button
              type="submit"
              className="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold transition"
            >
              Terbitkan Pengumuman
            </button>
          </form>

          {/* Info Rekening Pembayaran */}
          <div className="bg-white rounded-2xl border border-slate-200 p-6 space-y-3 shadow-2xs text-xs">
            <h3 className="text-sm font-bold text-slate-900">Rekening Penerimaan Sewa</h3>
            <div className="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
              <p className="font-bold text-slate-800">{prop.bankAccount.bankName}</p>
              <p className="text-base font-black text-orange-600">
                {prop.bankAccount.accountNumber}
              </p>
              <p className="text-slate-500">a.n {prop.bankAccount.accountHolder}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
