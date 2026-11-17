"use client";

import React, { createContext, useContext, useState, useEffect, ReactNode } from "react";
import {
  Property,
  Room,
  Tenant,
  Bill,
  Ticket,
  Expense,
  Announcement,
  AuditLog,
  Role,
  TicketStatus,
  BillStatus,
} from "@/types";
import {
  initialProperties,
  initialRooms,
  initialTenants,
  initialBills,
  initialTickets,
  initialExpenses,
  initialAnnouncements,
  initialAuditLogs,
} from "./mockData";

const STORAGE_KEY = "etokos_demo_storage_v1";

interface KosContextType {
  role: Role;
  setRole: (role: Role) => void;
  selectedPropertyId: string; // 'all' or propertyId
  setSelectedPropertyId: (id: string) => void;
  activeTenantId: string;
  setActiveTenantId: (id: string) => void;
  properties: Property[];
  rooms: Room[];
  tenants: Tenant[];
  bills: Bill[];
  tickets: Ticket[];
  expenses: Expense[];
  announcements: Announcement[];
  auditLogs: AuditLog[];
  // Actions
  addRoom: (room: Omit<Room, "id">) => { success: boolean; message: string };
  updateRoom: (id: string, data: Partial<Room>) => { success: boolean; message: string };
  deleteRoom: (id: string) => { success: boolean; message: string };
  generateMonthlyBills: (month: string, propertyId: string) => { count: number; message: string };
  verifyPayment: (billId: string, approved: boolean, note?: string) => void;
  submitPaymentProof: (
    billId: string,
    proof: { imageUrl: string; amount: number; method: "TRANSFER_BCA" | "TRANSFER_MANDIRI" | "QRIS" | "TUNAI" }
  ) => void;
  correctBill: (billId: string, correctionAmount: number, reason: string) => void;
  createTicket: (data: {
    propertyId: string;
    propertyName: string;
    roomId: string;
    roomNumber: string;
    tenantId: string;
    tenantName: string;
    category: Ticket["category"];
    title: string;
    description: string;
    priority: Ticket["priority"];
    imageUrl?: string;
  }) => Ticket;
  assignTechnician: (
    ticketId: string,
    technician: { name: string; phone: string; speciality: string }
  ) => void;
  resolveTicketWithCost: (
    ticketId: string,
    repairCost: number,
    completionNote: string
  ) => { success: boolean; expenseCreated?: Expense };
  addTicketComment: (ticketId: string, text: string) => void;
  addExpense: (expense: Omit<Expense, "id" | "createdAt">) => Expense;
  addAnnouncement: (announcement: Omit<Announcement, "id">) => void;
  resetDemoData: () => void;
}

const KosContext = createContext<KosContextType | undefined>(undefined);

export function KosProvider({ children }: { children: ReactNode }) {
  const [isInitialized, setIsInitialized] = useState(false);
  const [role, setRoleState] = useState<Role>("owner");
  const [selectedPropertyId, setSelectedPropertyId] = useState<string>("all");
  const [activeTenantId, setActiveTenantId] = useState<string>("t-1"); // Budi Santoso

  const [properties, setProperties] = useState<Property[]>(initialProperties);
  const [rooms, setRooms] = useState<Room[]>(initialRooms);
  const [tenants, setTenants] = useState<Tenant[]>(initialTenants);
  const [bills, setBills] = useState<Bill[]>(initialBills);
  const [tickets, setTickets] = useState<Ticket[]>(initialTickets);
  const [expenses, setExpenses] = useState<Expense[]>(initialExpenses);
  const [announcements, setAnnouncements] = useState<Announcement[]>(initialAnnouncements);
  const [auditLogs, setAuditLogs] = useState<AuditLog[]>(initialAuditLogs);

  // Load from localStorage on mount
  useEffect(() => {
    try {
      const saved = localStorage.getItem(STORAGE_KEY);
      if (saved) {
        const parsed = JSON.parse(saved);
        if (parsed.properties) setProperties(parsed.properties);
        if (parsed.rooms) setRooms(parsed.rooms);
        if (parsed.tenants) setTenants(parsed.tenants);
        if (parsed.bills) setBills(parsed.bills);
        if (parsed.tickets) setTickets(parsed.tickets);
        if (parsed.expenses) setExpenses(parsed.expenses);
        if (parsed.announcements) setAnnouncements(parsed.announcements);
        if (parsed.auditLogs) setAuditLogs(parsed.auditLogs);
        if (parsed.role) setRoleState(parsed.role);
        if (parsed.activeTenantId) setActiveTenantId(parsed.activeTenantId);
      }
    } catch (e) {
      console.warn("Failed to read from localStorage", e);
    } finally {
      setIsInitialized(true);
    }
  }, []);

  // Save to localStorage on change
  useEffect(() => {
    if (!isInitialized) return;
    try {
      const stateToSave = {
        properties,
        rooms,
        tenants,
        bills,
        tickets,
        expenses,
        announcements,
        auditLogs,
        role,
        activeTenantId,
      };
      localStorage.setItem(STORAGE_KEY, JSON.stringify(stateToSave));
    } catch (e) {
      console.warn("Failed to write to localStorage", e);
    }
  }, [
    isInitialized,
    properties,
    rooms,
    tenants,
    bills,
    tickets,
    expenses,
    announcements,
    auditLogs,
    role,
    activeTenantId,
  ]);

  const addLog = (
    action: string,
    category: AuditLog["category"],
    description: string,
    userName = role === "owner" ? "Owner Hendra" : "Penghuni Budi",
    userRole = role
  ) => {
    const newLog: AuditLog = {
      id: `log-${Date.now()}`,
      timestamp: new Date().toISOString().replace("T", " ").slice(0, 19),
      action,
      category,
      description,
      userName,
      userRole,
    };
    setAuditLogs((prev) => [newLog, ...prev]);
  };

  const setRole = (newRole: Role) => {
    setRoleState(newRole);
    addLog("GANTI_PERAN", "KONTRAK", `Beralih ke peran ${newRole.toUpperCase()}`);
  };

  // ROOM BUSINESS RULES:
  // Kamar terisi tidak bisa dihapus!
  const addRoom = (roomData: Omit<Room, "id">) => {
    const newId = `room-${Date.now()}`;
    const newRoom: Room = { ...roomData, id: newId };
    setRooms((prev) => [...prev, newRoom]);
    addLog("TAMBAH_KAMAR", "KAMAR", `Menambahkan kamar baru nomor ${roomData.roomNumber}`);
    return { success: true, message: `Kamar ${roomData.roomNumber} berhasil ditambahkan.` };
  };

  const updateRoom = (id: string, data: Partial<Room>) => {
    setRooms((prev) => prev.map((r) => (r.id === id ? { ...r, ...data } : r)));
    addLog("UPDATE_KAMAR", "KAMAR", `Memperbarui data kamar`);
    return { success: true, message: "Kamar berhasil diperbarui." };
  };

  const deleteRoom = (id: string) => {
    const targetRoom = rooms.find((r) => r.id === id);
    if (!targetRoom) return { success: false, message: "Kamar tidak ditemukan." };

    if (targetRoom.status === "TERISI" || targetRoom.currentTenantId) {
      return {
        success: false,
        message: `Aturan Bisnis: Kamar ${targetRoom.roomNumber} berstatus TERISI dan tidak dapat dihapus. Silakan lakukan proses check-out penghuni terlebih dahulu.`,
      };
    }

    setRooms((prev) => prev.filter((r) => r.id !== id));
    addLog("HAPUS_KAMAR", "KAMAR", `Menghapus kamar kosong nomor ${targetRoom.roomNumber}`);
    return { success: true, message: `Kamar ${targetRoom.roomNumber} berhasil dihapus.` };
  };

  // BILLING SYSTEM:
  const generateMonthlyBills = (month: string, propertyId: string) => {
    // Generate for all active tenants in specified property (or all properties)
    const targetTenants = tenants.filter(
      (t) => propertyId === "all" || t.propertyId === propertyId
    );

    let createdCount = 0;
    const newBills: Bill[] = [];

    targetTenants.forEach((tenant) => {
      // Check if bill already exists for this tenant & month
      const exists = bills.some(
        (b) => b.tenantId === tenant.id && b.billingMonth === month
      );
      if (!exists) {
        const prop = properties.find((p) => p.id === tenant.propertyId);
        const wifi = prop?.pricingRules.wifiFee || 50000;
        const clean = prop?.pricingRules.cleaningFee || 25000;
        const subtotal = tenant.monthlyRent + wifi + clean;

        const [y, m] = month.split("-");
        const monthsIndo = [
          "Januari",
          "Februari",
          "Maret",
          "April",
          "Mei",
          "Juni",
          "Juli",
          "Agustus",
          "September",
          "Oktober",
          "November",
          "Desember",
        ];
        const periodName = `${monthsIndo[parseInt(m, 10) - 1]} ${y}`;

        const bill: Bill = {
          id: `bill-${month}-${tenant.id}`,
          invoiceNumber: `INV/${y}${m}/${Math.floor(100 + Math.random() * 900)}`,
          propertyId: tenant.propertyId,
          tenantId: tenant.id,
          tenantName: tenant.name,
          roomId: tenant.roomId,
          roomNumber: tenant.roomNumber,
          billingMonth: month,
          periodName,
          dueDate: `${month}-10`,
          subtotal,
          lateFee: 0,
          totalAmount: subtotal,
          paidAmount: 0,
          remainingAmount: subtotal,
          status: "BELUM_BAYAR",
          items: [
            { id: `bi-${Date.now()}-1`, name: `Sewa Kamar #${tenant.roomNumber}`, amount: tenant.monthlyRent },
            { id: `bi-${Date.now()}-2`, name: "Iuran WiFi", amount: wifi },
            { id: `bi-${Date.now()}-3`, name: "Iuran Kebersihan & Sampah", amount: clean },
          ],
          paymentProofs: [],
          createdAt: `${month}-01`,
        };
        newBills.push(bill);
        createdCount++;
      }
    });

    if (createdCount > 0) {
      setBills((prev) => [...newBills, ...prev]);
      addLog(
        "GENERATE_TAGIHAN",
        "TAGIHAN",
        `Membuat ${createdCount} tagihan massal periode ${month}`
      );
    }

    return {
      count: createdCount,
      message:
        createdCount > 0
          ? `Berhasil membuat ${createdCount} tagihan baru periode ${month}.`
          : `Semua penghuni sudah memiliki tagihan untuk periode ${month}.`,
    };
  };

  const verifyPayment = (billId: string, approved: boolean, note?: string) => {
    setBills((prev) =>
      prev.map((bill) => {
        if (bill.id !== billId) return bill;
        if (approved) {
          const nowStr = new Date().toISOString().replace("T", " ").slice(0, 19);
          const updatedProofs = bill.paymentProofs.map((p) =>
            p.status === "PENDING"
              ? {
                  ...p,
                  status: "APPROVED" as const,
                  verifiedAt: nowStr,
                  verifiedBy: "Owner Hendra",
                  note: note || "Pembayaran diverifikasi valid",
                }
              : p
          );
          return {
            ...bill,
            status: "LUNAS" as BillStatus,
            paidAmount: bill.totalAmount,
            remainingAmount: 0,
            paidAt: nowStr.split(" ")[0],
            paymentProofs: updatedProofs,
          };
        } else {
          const updatedProofs = bill.paymentProofs.map((p) =>
            p.status === "PENDING"
              ? {
                  ...p,
                  status: "REJECTED" as const,
                  note: note || "Bukti transfer tidak valid / dana belum masuk",
                }
              : p
          );
          return {
            ...bill,
            paymentProofs: updatedProofs,
          };
        }
      })
    );

    const bill = bills.find((b) => b.id === billId);
    if (approved) {
      addLog(
        "VERIFIKASI_BAYAR",
        "PEMBAYARAN",
        `Verifikasi pembayaran Lunas untuk tagihan ${bill?.invoiceNumber} (${bill?.tenantName})`
      );
    } else {
      addLog(
        "TOLAK_BAYAR",
        "PEMBAYARAN",
        `Menolak bukti pembayaran untuk tagihan ${bill?.invoiceNumber}: ${note || "Tidak valid"}`
      );
    }
  };

  const submitPaymentProof = (
    billId: string,
    proof: { imageUrl: string; amount: number; method: "TRANSFER_BCA" | "TRANSFER_MANDIRI" | "QRIS" | "TUNAI" }
  ) => {
    const nowStr = new Date().toISOString().replace("T", " ").slice(0, 16);
    const newProof = {
      uploadedAt: nowStr,
      imageUrl: proof.imageUrl,
      amount: proof.amount,
      method: proof.method,
      status: "PENDING" as const,
      note: "Menunggu verifikasi owner",
    };

    setBills((prev) =>
      prev.map((b) => {
        if (b.id !== billId) return b;
        return {
          ...b,
          paymentProofs: [newProof, ...b.paymentProofs],
        };
      })
    );

    const b = bills.find((x) => x.id === billId);
    addLog(
      "UNGGAH_BUKTI_BAYAR",
      "PEMBAYARAN",
      `Penghuni ${b?.tenantName} mengunggah bukti pembayaran ${b?.invoiceNumber}`
    );
  };

  // Correction without deleting payment records
  const correctBill = (billId: string, correctionAmount: number, reason: string) => {
    setBills((prev) =>
      prev.map((b) => {
        if (b.id !== billId) return b;
        const newTotal = Math.max(0, b.subtotal + b.lateFee + correctionAmount);
        const remaining = Math.max(0, newTotal - b.paidAmount);
        const newStatus: BillStatus =
          remaining === 0 ? "LUNAS" : b.paidAmount > 0 ? "SEBAGIAN" : "BELUM_BAYAR";

        return {
          ...b,
          totalAmount: newTotal,
          remainingAmount: remaining,
          status: newStatus,
          paymentCorrectionNote: `Koreksi: ${correctionAmount < 0 ? "-" : "+"}${Math.abs(
            correctionAmount
          )} (${reason})`,
        };
      })
    );

    addLog(
      "KOREKSI_TAGIHAN",
      "TAGIHAN",
      `Koreksi tagihan ${billId}: ${correctionAmount} dengan alasan: "${reason}"`
    );
  };

  // TICKET MANAGEMENT & THE "WOW" FLOW
  const createTicket = (data: {
    propertyId: string;
    propertyName: string;
    roomId: string;
    roomNumber: string;
    tenantId: string;
    tenantName: string;
    category: Ticket["category"];
    title: string;
    description: string;
    priority: Ticket["priority"];
    imageUrl?: string;
  }) => {
    const ticketId = `tkt-${Date.now()}`;
    const newTicket: Ticket = {
      id: ticketId,
      ticketNumber: `TKT/${new Date().getFullYear()}/${Math.floor(100 + Math.random() * 900)}`,
      propertyId: data.propertyId,
      propertyName: data.propertyName,
      roomId: data.roomId,
      roomNumber: data.roomNumber,
      tenantId: data.tenantId,
      tenantName: data.tenantName,
      category: data.category,
      title: data.title,
      description: data.description,
      priority: data.priority,
      status: "BARU",
      imageUrl:
        data.imageUrl ||
        "https://images.unsplash.com/photo-1621905251918-48416bd8575a?w=600&auto=format&fit=crop&q=80",
      comments: [
        {
          id: `c-${Date.now()}`,
          authorName: data.tenantName,
          authorRole: "penghuni",
          text: data.description,
          createdAt: new Date().toISOString().replace("T", " ").slice(0, 16),
        },
      ],
      createdAt: new Date().toISOString().replace("T", " ").slice(0, 16),
    };

    setTickets((prev) => [newTicket, ...prev]);
    addLog(
      "TIKET_BARU",
      "KELUHAN",
      `Penghuni ${data.tenantName} (Kmr ${data.roomNumber}) melapor: ${data.title}`,
      data.tenantName,
      "penghuni"
    );

    return newTicket;
  };

  const assignTechnician = (
    ticketId: string,
    technician: { name: string; phone: string; speciality: string }
  ) => {
    setTickets((prev) =>
      prev.map((t) => {
        if (t.id !== ticketId) return t;
        return {
          ...t,
          status: "DIPROSES" as TicketStatus,
          assignedTechnician: technician,
          comments: [
            ...t.comments,
            {
              id: `c-${Date.now()}`,
              authorName: "Owner Hendra",
              authorRole: "owner",
              text: `Menugaskan ${technician.name} (${technician.speciality}) untuk perbaikan. No telp: ${technician.phone}`,
              createdAt: new Date().toISOString().replace("T", " ").slice(0, 16),
            },
          ],
        };
      })
    );

    const t = tickets.find((x) => x.id === ticketId);
    addLog(
      "PENUGASAN_TEKNISI",
      "KELUHAN",
      `Menugaskan ${technician.name} untuk tiket "${t?.title}" (${t?.ticketNumber})`
    );
  };

  // THE WOW FLOW STEP 4:
  // When a ticket is completed, if repairCost > 0, an expense is automatically recorded in accounting!
  const resolveTicketWithCost = (
    ticketId: string,
    repairCost: number,
    completionNote: string
  ) => {
    const targetTicket = tickets.find((t) => t.id === ticketId);
    if (!targetTicket) return { success: false };

    const nowStr = new Date().toISOString().replace("T", " ").slice(0, 16);
    const dateOnly = nowStr.split(" ")[0];

    // 1. Update ticket to SELESAI
    setTickets((prev) =>
      prev.map((t) => {
        if (t.id !== ticketId) return t;
        return {
          ...t,
          status: "SELESAI" as TicketStatus,
          repairCost,
          completionNote,
          completedAt: nowStr,
          comments: [
            ...t.comments,
            {
              id: `c-${Date.now()}`,
              authorName: "Teknisi / Pengelola",
              authorRole: "owner",
              text: `Perbaikan selesai. Catatan: ${completionNote}. Biaya jasa & suku cadang: Rp ${repairCost.toLocaleString(
                "id-ID"
              )}`,
              createdAt: nowStr,
            },
          ],
        };
      })
    );

    let createdExpense: Expense | undefined;

    // 2. Automatically record in Accounting if cost > 0
    if (repairCost > 0) {
      createdExpense = {
        id: `exp-${Date.now()}`,
        propertyId: targetTicket.propertyId,
        date: dateOnly,
        category: "Perbaikan & Perawatan",
        title: `Perbaikan: ${targetTicket.title} (Kamar #${targetTicket.roomNumber})`,
        amount: Math.max(0, repairCost),
        description: `Biaya perbaikan dari tiket ${targetTicket.ticketNumber}. Catatan: ${completionNote}`,
        recipient: targetTicket.assignedTechnician?.name || "Teknisi Vendor",
        relatedTicketId: ticketId,
        createdAt: dateOnly,
      };

      setExpenses((prev) => [createdExpense!, ...prev]);

      addLog(
        "PENGELUARAN_OTOMATIS",
        "PENGELUARAN",
        `[ALUR WOW] Biaya perbaikan tiket ${targetTicket.ticketNumber} sebesar Rp ${repairCost.toLocaleString(
          "id-ID"
        )} otomatis dicatat ke Pengeluaran Akuntansi!`
      );
    } else {
      addLog(
        "TIKET_SELESAI",
        "KELUHAN",
        `Tiket ${targetTicket.ticketNumber} (${targetTicket.title}) ditandai selesai tanpa biaya tambahan.`
      );
    }

    return { success: true, expenseCreated: createdExpense };
  };

  const addTicketComment = (ticketId: string, text: string) => {
    const authorName = role === "owner" ? "Owner Hendra" : "Penghuni Budi";
    const newComment = {
      id: `c-${Date.now()}`,
      authorName,
      authorRole: role,
      text,
      createdAt: new Date().toISOString().replace("T", " ").slice(0, 16),
    };

    setTickets((prev) =>
      prev.map((t) => {
        if (t.id !== ticketId) return t;
        return {
          ...t,
          comments: [...t.comments, newComment],
        };
      })
    );
  };

  // EXPENSE MANAGEMENT:
  const addExpense = (data: Omit<Expense, "id" | "createdAt">) => {
    const newExpense: Expense = {
      ...data,
      id: `exp-${Date.now()}`,
      amount: Math.max(0, data.amount),
      createdAt: new Date().toISOString().split("T")[0],
    };

    setExpenses((prev) => [newExpense, ...prev]);
    addLog(
      "TAMBAH_PENGELUARAN",
      "PENGELUARAN",
      `Mencatat pengeluaran ${data.title} sebesar Rp ${data.amount.toLocaleString("id-ID")}`
    );
    return newExpense;
  };

  const addAnnouncement = (data: Omit<Announcement, "id">) => {
    const newAnn: Announcement = {
      ...data,
      id: `ann-${Date.now()}`,
    };
    setAnnouncements((prev) => [newAnn, ...prev]);
    addLog("PENGUMUMAN_BARU", "KONTRAK", `Menerbitkan pengumuman: ${data.title}`);
  };

  const resetDemoData = () => {
    localStorage.removeItem(STORAGE_KEY);
    setProperties(initialProperties);
    setRooms(initialRooms);
    setTenants(initialTenants);
    setBills(initialBills);
    setTickets(initialTickets);
    setExpenses(initialExpenses);
    setAnnouncements(initialAnnouncements);
    setAuditLogs(initialAuditLogs);
    setRoleState("owner");
    setSelectedPropertyId("all");
    setActiveTenantId("t-1");
    addLog("RESET_DEMO", "KONTRAK", "Data demo berhasil direset ke kondisi awal.");
  };

  return (
    <KosContext.Provider
      value={{
        role,
        setRole,
        selectedPropertyId,
        setSelectedPropertyId,
        activeTenantId,
        setActiveTenantId,
        properties,
        rooms,
        tenants,
        bills,
        tickets,
        expenses,
        announcements,
        auditLogs,
        addRoom,
        updateRoom,
        deleteRoom,
        generateMonthlyBills,
        verifyPayment,
        submitPaymentProof,
        correctBill,
        createTicket,
        assignTechnician,
        resolveTicketWithCost,
        addTicketComment,
        addExpense,
        addAnnouncement,
        resetDemoData,
      }}
    >
      {children}
    </KosContext.Provider>
  );
}

export function useKosStore() {
  const context = useContext(KosContext);
  if (!context) {
    throw new Error("useKosStore must be used within a KosProvider");
  }
  return context;
}
