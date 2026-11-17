"use client";

import React from "react";
import { useKosStore } from "@/lib/store";
import { formatTanggal } from "@/lib/formatters";
import { Bell, Calendar, UserCheck } from "lucide-react";

export default function TenantPengumumanPage() {
  const { announcements } = useKosStore();

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-extrabold text-slate-900 tracking-tight">
          Papan Pengumuman & Informasi Kos
        </h1>
        <p className="text-xs text-slate-500 mt-0.5">
          Pemberitahuan resmi dari pemilik dan pengelola kos mengenai perawatan fasilitas, aturan, dan jadwal kegiatan.
        </p>
      </div>

      <div className="space-y-4">
        {announcements.map((ann) => (
          <div
            key={ann.id}
            className="bg-white rounded-2xl border border-slate-200 p-6 shadow-2xs space-y-3"
          >
            <div className="flex items-center justify-between">
              <span
                className={`px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase ${
                  ann.category === "PENTING"
                    ? "bg-rose-100 text-rose-800 border border-rose-200"
                    : ann.category === "PERAWATAN"
                    ? "bg-amber-100 text-amber-800 border border-amber-200"
                    : "bg-blue-100 text-blue-800 border border-blue-200"
                }`}
              >
                {ann.category}
              </span>
              <span className="text-xs text-slate-400 flex items-center space-x-1">
                <Calendar className="w-3.5 h-3.5" />
                <span>{formatTanggal(ann.date)}</span>
              </span>
            </div>

            <h2 className="text-base font-bold text-slate-900">{ann.title}</h2>
            <p className="text-xs sm:text-sm text-slate-600 leading-relaxed">{ann.content}</p>

            <div className="pt-2 border-t border-slate-100 flex items-center space-x-1.5 text-[11px] text-slate-500">
              <UserCheck className="w-3.5 h-3.5 text-orange-500" />
              <span>Diterbitkan oleh: <strong>{ann.author}</strong></span>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
