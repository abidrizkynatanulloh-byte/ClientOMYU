"use client";

import React, { useState } from "react";
import { useRouter } from "next/navigation";
import { useKosStore } from "@/lib/store";
import {
  getPriorityMeta,
  getTicketStatusMeta,
  formatRupiah,
} from "@/lib/formatters";
import { Ticket, TicketPriority } from "@/types";
import {
  Wrench,
  Camera,
  Send,
  Sparkles,
  CheckCircle2,
  ArrowRight,
  Clock,
} from "lucide-react";

export default function TenantKeluhanPage() {
  const router = useRouter();
  const {
    activeTenantId,
    tenants,
    properties,
    tickets,
    createTicket,
    setRole,
  } = useKosStore();

  const currentTenant = tenants.find((t) => t.id === activeTenantId) || tenants[0];
  const currentProperty =
    properties.find((p) => p.id === currentTenant.propertyId) || properties[0];

  const myTickets = tickets.filter((t) => t.tenantId === currentTenant.id);

  const [category, setCategory] = useState<Ticket["category"]>("AC");
  const [priority, setPriority] = useState<TicketPriority>("TINGGI");
  const [title, setTitle] = useState("AC Kamar 102 Kurang Dingin & Bocor");
  const [description, setDescription] = useState(
    "Unit indoor AC Daikin kamar 102 meneteskan air ke meja kerja sejak pagi dan hembusan angin tidak dingin."
  );
  const [imageUrl, setImageUrl] = useState(
    "https://images.unsplash.com/photo-1621905251918-48416bd8575a?w=600&auto=format&fit=crop&q=80"
  );
  const [createdSuccess, setCreatedSuccess] = useState<Ticket | null>(null);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!title.trim() || !description.trim()) return;
    const tkt = createTicket({
      propertyId: currentProperty.id,
      propertyName: currentProperty.name,
      roomId: currentTenant.roomId,
      roomNumber: currentTenant.roomNumber,
      tenantId: currentTenant.id,
      tenantName: currentTenant.name,
      category,
      title,
      description,
      priority,
      imageUrl,
    });
    setCreatedSuccess(tkt);
  };

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-extrabold text-slate-900 tracking-tight">
          Lapor Keluhan & Perbaikan Fasilitas
        </h1>
        <p className="text-xs text-slate-500 mt-0.5">
          Kirimkan laporan kendala fasilitas kamar beserta foto pendukung. Pengelola akan segera menugaskan teknisi.
        </p>
      </div>

      {/* Celebration & Direct Continuation to Owner Kanban for the WOW FLOW */}
      {createdSuccess && (
        <div className="p-5 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-lg flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div className="flex items-start space-x-3">
            <CheckCircle2 className="w-6 h-6 text-yellow-300 flex-shrink-0 mt-0.5" />
            <div>
              <span className="px-2 py-0.5 rounded-full bg-white/20 text-[10px] font-bold uppercase">
                Langkah 1 Alur "Wow" Selesai!
              </span>
              <h3 className="text-base font-extrabold mt-1">
                Tiket #{createdSuccess.ticketNumber} Berhasil Dikirim ke Pengelola!
              </h3>
              <p className="text-xs text-emerald-100 mt-0.5">
                Sekarang beralihlah ke Portal Owner untuk menugaskan teknisi, menyelesaikan perbaikan, dan melihat biaya masuk otomatis ke Laba Rugi.
              </p>
            </div>
          </div>

          <button
            onClick={() => {
              setRole("owner");
              router.push("/owner/keluhan");
            }}
            className="px-4 py-2.5 rounded-xl bg-white text-emerald-800 font-bold text-xs hover:bg-emerald-50 transition shadow flex items-center space-x-1.5 whitespace-nowrap self-start sm:self-center"
          >
            <span>Lanjut ke Portal Owner (Kanban)</span>
            <ArrowRight className="w-4 h-4" />
          </button>
        </div>
      )}

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Form Lapor Keluhan */}
        <form
          onSubmit={handleSubmit}
          className="bg-white rounded-2xl border border-slate-200 p-6 space-y-4 shadow-2xs text-xs"
        >
          <div className="flex items-center justify-between border-b border-slate-100 pb-3">
            <div className="flex items-center space-x-2">
              <Wrench className="w-4 h-4 text-orange-600" />
              <h2 className="text-sm font-bold text-slate-900">Formulir Keluhan Baru</h2>
            </div>
            <span className="px-2 py-0.5 rounded bg-orange-50 text-orange-700 font-bold text-[10px]">
              Kamar #{currentTenant.roomNumber}
            </span>
          </div>

          <div className="grid grid-cols-2 gap-3">
            <div className="space-y-1">
              <label className="font-bold text-slate-700">Kategori Fasilitas *</label>
              <select
                value={category}
                onChange={(e) => setCategory(e.target.value as any)}
                className="w-full px-3 py-2 border rounded-lg bg-white font-semibold"
              >
                <option value="AC">AC & Pendingin</option>
                <option value="Plumbing & Sanitasi">Plumbing & Air Kamar Mandi</option>
                <option value="Listrik">Listrik & Lampu</option>
                <option value="WiFi & Jaringan">WiFi & Jaringan</option>
                <option value="Kamar & Mebel">Kamar & Mebel</option>
                <option value="Kebersihan & Area Umum">Kebersihan & Area Umum</option>
              </select>
            </div>

            <div className="space-y-1">
              <label className="font-bold text-slate-700">Tingkat Urgensi *</label>
              <select
                value={priority}
                onChange={(e) => setPriority(e.target.value as any)}
                className="w-full px-3 py-2 border rounded-lg bg-white font-semibold"
              >
                <option value="RENDAH">Rendah</option>
                <option value="SEDANG">Sedang</option>
                <option value="TINGGI">Tinggi</option>
                <option value="DARURAT">Darurat</option>
              </select>
            </div>
          </div>

          <div className="space-y-1">
            <label className="font-bold text-slate-700">Judul Keluhan *</label>
            <input
              type="text"
              required
              value={title}
              onChange={(e) => setTitle(e.target.value)}
              className="w-full px-3 py-2 border rounded-lg font-semibold text-slate-900"
            />
          </div>

          <div className="space-y-1">
            <label className="font-bold text-slate-700">Deskripsi Detail Kerusakan *</label>
            <textarea
              rows={3}
              required
              value={description}
              onChange={(e) => setDescription(e.target.value)}
              className="w-full px-3 py-2 border rounded-lg text-slate-700"
            />
          </div>

          <div className="space-y-1.5">
            <label className="font-bold text-slate-700 flex items-center space-x-1.5">
              <Camera className="w-3.5 h-3.5 text-orange-600" />
              <span>Lampiran Foto Kondisi Fasilitas</span>
            </label>
            <div className="relative h-32 rounded-xl overflow-hidden border border-slate-200">
              <img
                src={imageUrl}
                alt="Preview keluhan"
                className="w-full h-full object-cover"
              />
              <span className="absolute bottom-2 left-2 px-2.5 py-1 rounded bg-black/70 text-white text-[10px]">
                📸 foto-ac-kamar-102.jpg (Terlampir)
              </span>
            </div>
          </div>

          <button
            type="submit"
            className="w-full py-2.5 px-4 rounded-xl bg-orange-600 hover:bg-orange-700 text-white font-bold flex items-center justify-center space-x-2 transition shadow-sm"
          >
            <Send className="w-4 h-4" />
            <span>Kirim Laporan Keluhan</span>
          </button>
        </form>

        {/* Riwayat Tiket Keluhan Saya */}
        <div className="bg-white rounded-2xl border border-slate-200 p-6 space-y-4 shadow-2xs">
          <h2 className="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">
            Status Laporan Keluhan Saya ({myTickets.length})
          </h2>

          <div className="space-y-3">
            {myTickets.map((tkt) => {
              const sMeta = getTicketStatusMeta(tkt.status);
              const pMeta = getPriorityMeta(tkt.priority);
              return (
                <div
                  key={tkt.id}
                  className="p-4 rounded-xl border border-slate-200 bg-slate-50/50 space-y-2.5 text-xs"
                >
                  <div className="flex items-center justify-between">
                    <span className="font-bold text-slate-500 text-[11px]">
                      {tkt.ticketNumber} • {tkt.category}
                    </span>
                    <div className="flex items-center space-x-1.5">
                      <span className={`px-2 py-0.5 rounded text-[10px] font-bold ${pMeta.bg}`}>
                        {pMeta.label}
                      </span>
                      <span
                        className={`px-2.5 py-0.5 rounded-full text-[10px] font-bold border ${sMeta.bg}`}
                      >
                        {sMeta.label}
                      </span>
                    </div>
                  </div>

                  <h3 className="text-sm font-bold text-slate-900">{tkt.title}</h3>
                  <p className="text-slate-600">{tkt.description}</p>

                  {tkt.assignedTechnician && (
                    <div className="p-2.5 rounded-lg bg-blue-50 border border-blue-200 text-blue-900 text-[11px]">
                      🔧 <strong>Teknisi Ditugaskan:</strong> {tkt.assignedTechnician.name} (
                      {tkt.assignedTechnician.phone})
                    </div>
                  )}

                  {tkt.status === "SELESAI" && (
                    <div className="p-2.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-900 text-[11px]">
                      ✅ <strong>Selesai Diperbaiki:</strong> {tkt.completionNote}
                    </div>
                  )}
                </div>
              );
            })}
          </div>
        </div>
      </div>
    </div>
  );
}
