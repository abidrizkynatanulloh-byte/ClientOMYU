"use client";

import React, { useState } from "react";
import { useKosStore } from "@/lib/store";
import { History, ShieldCheck, Filter } from "lucide-react";

export default function OwnerAktivitasPage() {
  const { auditLogs } = useKosStore();
  const [catFilter, setCatFilter] = useState<string>("ALL");

  const filteredLogs = auditLogs.filter(
    (log) => catFilter === "ALL" || log.category === catFilter
  );

  return (
    <div className="space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-extrabold text-slate-900 tracking-tight">
            Log Aktivitas & Audit Trail Sistem
          </h1>
          <p className="text-xs text-slate-500 mt-0.5">
            Rekam jejak seluruh perubahan penting: tagihan, verifikasi pembayaran, koreksi, keluhan, dan pengeluaran.
          </p>
        </div>

        <div className="inline-flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold">
          <ShieldCheck className="w-4 h-4 text-emerald-600" />
          <span>Audit Log Aktif ({auditLogs.length} Entri)</span>
        </div>
      </div>

      {/* Category Filter */}
      <div className="bg-white rounded-2xl border border-slate-200 p-4 flex flex-wrap items-center gap-2 text-xs">
        <span className="font-bold text-slate-600 flex items-center space-x-1 mr-2">
          <Filter className="w-3.5 h-3.5 text-orange-500" />
          <span>Filter Kategori:</span>
        </span>
        {["ALL", "TAGIHAN", "PEMBAYARAN", "KELUHAN", "PENGELUARAN", "KAMAR", "KONTRAK"].map(
          (cat) => (
            <button
              key={cat}
              onClick={() => setCatFilter(cat)}
              className={`px-3 py-1.5 rounded-lg font-bold transition ${
                catFilter === cat
                  ? "bg-slate-900 text-white"
                  : "bg-slate-100 text-slate-600 hover:bg-slate-200"
              }`}
            >
              {cat === "ALL" ? "Semua Aktivitas" : cat}
            </button>
          )
        )}
      </div>

      {/* Timeline List */}
      <div className="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs">
        <div className="divide-y divide-slate-100">
          {filteredLogs.map((log) => (
            <div
              key={log.id}
              className="p-4 hover:bg-slate-50/70 transition flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs"
            >
              <div className="space-y-1">
                <div className="flex items-center space-x-2">
                  <span className="px-2 py-0.5 rounded bg-orange-100 text-orange-800 font-bold text-[10px]">
                    {log.category}
                  </span>
                  <span className="font-mono text-[11px] font-bold text-slate-700">
                    {log.action}
                  </span>
                  <span className="text-[11px] text-slate-400">• {log.timestamp}</span>
                </div>
                <p className="text-slate-800 font-medium">{log.description}</p>
              </div>

              <div className="text-right flex sm:flex-col items-center sm:items-end justify-between">
                <span className="font-bold text-slate-900">{log.userName}</span>
                <span className="text-[10px] uppercase font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                  Peran: {log.userRole}
                </span>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
