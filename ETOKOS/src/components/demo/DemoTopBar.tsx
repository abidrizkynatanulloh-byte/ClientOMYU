"use client";

import React, { useState } from "react";
import { useKosStore } from "@/lib/store";
import { useRouter, usePathname } from "next/navigation";
import {
  ShieldCheck,
  UserCheck,
  Globe,
  Sparkles,
  RotateCcw,
  Building2,
  ChevronDown,
} from "lucide-react";
import { WowFlowModal } from "./WowFlowModal";

export function DemoTopBar() {
  const {
    role,
    setRole,
    selectedPropertyId,
    setSelectedPropertyId,
    properties,
    resetDemoData,
  } = useKosStore();
  const router = useRouter();
  const pathname = usePathname();
  const [showWowModal, setShowWowModal] = useState(false);
  const [showPropertyMenu, setShowPropertyMenu] = useState(false);

  const handleRoleChange = (newRole: "owner" | "penghuni" | "pengunjung") => {
    setRole(newRole);
    if (newRole === "owner") {
      router.push("/owner/dashboard");
    } else if (newRole === "penghuni") {
      router.push("/penghuni");
    } else {
      router.push("/");
    }
  };

  const currentPropName =
    selectedPropertyId === "all"
      ? "Semua Properti (24 Kamar)"
      : properties.find((p) => p.id === selectedPropertyId)?.name || "Pilih Properti";

  return (
    <>
      <div className="sticky top-0 z-40 bg-slate-900 text-white border-b border-slate-800 shadow-md">
        <div className="max-w-7xl mx-auto px-3 sm:px-6 py-2 flex flex-wrap items-center justify-between gap-2 text-xs">
          {/* Left: Brand Badge & Wow Flow CTA */}
          <div className="flex items-center space-x-2">
            <span className="inline-flex items-center px-2 py-0.5 rounded-full bg-orange-500/20 text-orange-400 font-bold text-[10px] tracking-wide uppercase border border-orange-500/30">
              DEMO MODE
            </span>
            <button
              onClick={() => setShowWowModal(true)}
              className="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-md bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold transition shadow-sm"
              title="Lihat Alur 'Wow': Penghuni Lapor AC Rusak -> Owner Tugaskan -> Selesai -> Biaya Masuk Pengeluaran Otomatis"
            >
              <Sparkles className="w-3.5 h-3.5 animate-pulse text-yellow-200" />
              <span>Alur "Wow": Lapor AC → Laba Rugi</span>
            </button>
          </div>

          {/* Center: Property Selector */}
          <div className="relative">
            <button
              onClick={() => setShowPropertyMenu(!showPropertyMenu)}
              className="flex items-center space-x-1.5 px-2.5 py-1 rounded-md bg-slate-800 hover:bg-slate-700 text-slate-200 transition border border-slate-700"
            >
              <Building2 className="w-3.5 h-3.5 text-slate-400" />
              <span className="font-medium truncate max-w-[150px] sm:max-w-none">
                {currentPropName}
              </span>
              <ChevronDown className="w-3 h-3 text-slate-400" />
            </button>

            {showPropertyMenu && (
              <div className="absolute left-0 mt-1 w-56 rounded-lg bg-slate-800 border border-slate-700 shadow-xl z-50 py-1">
                <button
                  onClick={() => {
                    setSelectedPropertyId("all");
                    setShowPropertyMenu(false);
                  }}
                  className={`w-full text-left px-3 py-1.5 text-xs hover:bg-slate-700 transition ${
                    selectedPropertyId === "all" ? "text-orange-400 font-bold" : "text-slate-300"
                  }`}
                >
                  Semua Properti (24 Kamar)
                </button>
                {properties.map((p) => (
                  <button
                    key={p.id}
                    onClick={() => {
                      setSelectedPropertyId(p.id);
                      setShowPropertyMenu(false);
                    }}
                    className={`w-full text-left px-3 py-1.5 text-xs hover:bg-slate-700 transition ${
                      selectedPropertyId === p.id ? "text-orange-400 font-bold" : "text-slate-300"
                    }`}
                  >
                    {p.name} ({p.totalRooms} Kamar - {p.city})
                  </button>
                ))}
              </div>
            )}
          </div>

          {/* Right: Role Switcher & Reset */}
          <div className="flex items-center space-x-1.5">
            <div className="bg-slate-800 p-0.5 rounded-lg border border-slate-700 flex items-center">
              <button
                onClick={() => handleRoleChange("owner")}
                className={`px-2.5 py-1 rounded-md font-medium transition flex items-center space-x-1 ${
                  role === "owner" && pathname.startsWith("/owner")
                    ? "bg-orange-500 text-white font-bold shadow-sm"
                    : "text-slate-300 hover:text-white hover:bg-slate-700/50"
                }`}
                title="Beralih ke Portal Pemilik / Pengelola Kos"
              >
                <ShieldCheck className="w-3.5 h-3.5 text-amber-300" />
                <span>Owner</span>
              </button>

              <button
                onClick={() => handleRoleChange("penghuni")}
                className={`px-2.5 py-1 rounded-md font-medium transition flex items-center space-x-1 ${
                  role === "penghuni" && pathname.startsWith("/penghuni")
                    ? "bg-orange-500 text-white font-bold shadow-sm"
                    : "text-slate-300 hover:text-white hover:bg-slate-700/50"
                }`}
                title="Beralih ke Portal Penghuni (Budi Santoso - Kmr 102)"
              >
                <UserCheck className="w-3.5 h-3.5 text-sky-300" />
                <span>Penghuni</span>
              </button>

              <button
                onClick={() => handleRoleChange("pengunjung")}
                className={`px-2.5 py-1 rounded-md font-medium transition flex items-center space-x-1 ${
                  role === "pengunjung" || (!pathname.startsWith("/owner") && !pathname.startsWith("/penghuni") && pathname !== "/login")
                    ? "bg-orange-500 text-white font-bold shadow-sm"
                    : "text-slate-300 hover:text-white hover:bg-slate-700/50"
                }`}
                title="Beralih ke Tampilan Pengunjung / Listing Kos Publik"
              >
                <Globe className="w-3.5 h-3.5 text-emerald-300" />
                <span>Publik</span>
              </button>
            </div>

            <button
              onClick={() => {
                if (confirm("Reset seluruh data demo ke kondisi awal?")) {
                  resetDemoData();
                  alert("Data demo berhasil direset!");
                }
              }}
              className="p-1.5 rounded-md bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 transition"
              title="Reset Data Demo"
            >
              <RotateCcw className="w-3.5 h-3.5" />
            </button>
          </div>
        </div>
      </div>

      <WowFlowModal isOpen={showWowModal} onClose={() => setShowWowModal(false)} />
    </>
  );
}
