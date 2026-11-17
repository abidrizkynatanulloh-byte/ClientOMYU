"use client";

import React from "react";
import Link from "next/link";
import { usePathname } from "next/navigation";
import { useKosStore } from "@/lib/store";
import { Receipt, Wrench, Bell, User, Building, LogOut } from "lucide-react";

export default function TenantLayout({ children }: { children: React.ReactNode }) {
  const pathname = usePathname();
  const { tenants, activeTenantId, setRole } = useKosStore();

  const currentTenant = tenants.find((t) => t.id === activeTenantId) || tenants[0];

  const tabs = [
    { href: "/penghuni", label: "Tagihan & Kontrak", icon: Receipt },
    { href: "/penghuni/keluhan", label: "Lapor Keluhan", icon: Wrench },
    { href: "/penghuni/pengumuman", label: "Pengumuman", icon: Bell },
  ];

  return (
    <div className="flex-1 bg-slate-50 min-h-[calc(100vh-41px)] flex flex-col">
      {/* Tenant Top Bar */}
      <header className="bg-white border-b border-slate-200 sticky top-[41px] z-30 shadow-xs">
        <div className="max-w-5xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
          <div className="flex items-center space-x-3">
            <img
              src={currentTenant.avatarUrl}
              alt={currentTenant.name}
              className="w-10 h-10 rounded-full object-cover border-2 border-orange-500 shadow-xs"
            />
            <div>
              <div className="flex items-center space-x-2">
                <h1 className="text-sm font-bold text-slate-900">{currentTenant.name}</h1>
                <span className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-800 border border-orange-200">
                  Kamar #{currentTenant.roomNumber}
                </span>
              </div>
              <p className="text-[11px] text-slate-500 flex items-center space-x-1">
                <Building className="w-3 h-3 text-slate-400" />
                <span>OMYU Living BSD • Masuk: 1 Ags 2025</span>
              </p>
            </div>
          </div>

          <div className="flex items-center space-x-2">
            <button
              onClick={() => setRole("owner")}
              className="text-xs text-orange-600 hover:text-orange-700 bg-orange-50 hover:bg-orange-100 px-3 py-1.5 rounded-lg font-medium transition"
            >
              Beralih ke Owner
            </button>
          </div>
        </div>

        {/* Tab Links */}
        <div className="max-w-5xl mx-auto px-4 sm:px-6 flex space-x-6 border-t border-slate-100">
          {tabs.map((tab) => {
            const isActive = pathname === tab.href;
            const Icon = tab.icon;
            return (
              <Link
                key={tab.href}
                href={tab.href}
                className={`flex items-center space-x-2 py-3 text-xs font-semibold border-b-2 transition ${
                  isActive
                    ? "border-orange-500 text-orange-600"
                    : "border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300"
                }`}
              >
                <Icon className="w-4 h-4" />
                <span>{tab.label}</span>
              </Link>
            );
          })}
        </div>
      </header>

      {/* Main Content */}
      <main className="flex-1 max-w-5xl w-full mx-auto px-4 sm:px-6 py-6">{children}</main>
    </div>
  );
}
