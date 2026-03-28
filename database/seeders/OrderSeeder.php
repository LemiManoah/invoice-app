<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $orders = [
            [
                'order_number' => 'ORD-2026-001',
                'customer_id' => 1, // John Anderson
                'currency_id' => 1,
                'order_date' => now()->subDays(60),
                'promised_delivery_date' => now()->subDays(30),
                'actual_completion_date' => now()->subDays(28),
                'status' => 'completed',
                'priority' => 'normal',
                'notes' => 'Classic navy business suit',
                'assigned_to' => 1,
                'created_by' => 1,
            ],
            [
                'order_number' => 'ORD-2026-002',
                'customer_id' => 2, // Sarah Mitchell
                'currency_id' => 2,
                'order_date' => now()->subDays(45),
                'promised_delivery_date' => now()->subDays(15),
                'actual_completion_date' => null,
                'status' => 'in_progress',
                'priority' => 'high',
                'notes' => 'Power suit collection for board meetings',
                'assigned_to' => 1,
                'created_by' => 1,
            ],
            [
                'order_number' => 'ORD-2026-003',
                'customer_id' => 3, // Michael Chen
                'currency_id' => 1,
                'order_date' => now()->subDays(90),
                'promised_delivery_date' => now()->subDays(60),
                'actual_completion_date' => now()->subDays(58),
                'status' => 'completed',
                'priority' => 'normal',
                'notes' => 'Wedding tuxedo and formal wear',
                'assigned_to' => 1,
                'created_by' => 1,
            ],
            [
                'order_number' => 'ORD-2026-004',
                'customer_id' => 4, // Emily Rodriguez
                'currency_id' => 1,
                'order_date' => now()->subDays(20),
                'promised_delivery_date' => now()->addDays(10),
                'actual_completion_date' => null,
                'status' => 'pending',
                'priority' => 'normal',
                'notes' => 'Modern fashion-forward collection',
                'assigned_to' => 1,
                'created_by' => 1,
            ],
            [
                'order_number' => 'ORD-2026-005',
                'customer_id' => 5, // David Thompson
                'currency_id' => 1,
                'order_date' => now()->subDays(10),
                'promised_delivery_date' => now()->addDays(20),
                'actual_completion_date' => null,
                'status' => 'pending',
                'priority' => 'low',
                'notes' => 'Seasonal wardrobe update',
                'assigned_to' => 1,
                'created_by' => 1,
            ],
        ];

        Order::insert($orders);

        // Get the inserted orders with their IDs
        $insertedOrders = Order::all();

        $orderItems = [
            // Order 1 - John Anderson's business suit
            [
                'order_id' => $insertedOrders[0]->id,
                'garment_type' => 'suit',
                'description' => 'Navy business suit with pinstripes',
                'quantity' => 1,
                'unit_price' => 1200.00,
                'style_notes' => 'Classic two-button design, slim fit',
                'fabric_details' => 'Wool blend, 95% wool, 5% elastane',
                'color' => 'Navy blue with white pinstripes',
                'lining_details' => 'Silk lining in navy blue',
                'button_details' => 'Horn buttons',
                'monogram_text' => 'JA',
                'urgent_flag' => false,
            ],
            // Order 2 - Sarah Mitchell's power suits
            [
                'order_id' => $insertedOrders[1]->id,
                'garment_type' => 'suit',
                'description' => 'Charcoal grey power suit',
                'quantity' => 2,
                'unit_price' => 1500.00,
                'style_notes' => 'Executive cut, shoulder pads',
                'fabric_details' => 'Premium wool, 100% merino',
                'color' => 'Charcoal grey',
                'lining_details' => 'Silk lining in burgundy',
                'button_details' => 'Mother of pearl buttons',
                'monogram_text' => 'SM',
                'urgent_flag' => true,
            ],
            [
                'order_id' => $insertedOrders[1]->id,
                'garment_type' => 'blouse',
                'description' => 'White silk blouses',
                'quantity' => 3,
                'unit_price' => 200.00,
                'style_notes' => 'French cuffs, collarless design',
                'fabric_details' => '100% silk',
                'color' => 'White',
                'lining_details' => 'No lining',
                'button_details' => 'Fabric covered buttons',
                'monogram_text' => '',
                'urgent_flag' => true,
            ],
            // Order 3 - Michael Chen's wedding wear
            [
                'order_id' => $insertedOrders[2]->id,
                'garment_type' => 'tuxedo',
                'description' => 'Black tuxedo with tails',
                'quantity' => 1,
                'unit_price' => 2500.00,
                'style_notes' => 'Traditional formal wear',
                'fabric_details' => 'Wool barathea, 100% wool',
                'color' => 'Black',
                'lining_details' => 'Black satin lining',
                'button_details' => 'Black satin covered buttons',
                'monogram_text' => 'MC',
                'urgent_flag' => false,
            ],
            [
                'order_id' => $insertedOrders[2]->id,
                'garment_type' => 'vest',
                'description' => 'Matching waistcoat',
                'quantity' => 1,
                'unit_price' => 300.00,
                'style_notes' => 'Double-breasted design',
                'fabric_details' => 'Same as tuxedo',
                'color' => 'Black',
                'lining_details' => 'Black satin',
                'button_details' => 'Satin covered buttons',
                'monogram_text' => '',
                'urgent_flag' => false,
            ],
            // Order 4 - Emily Rodriguez's modern collection
            [
                'order_id' => $insertedOrders[3]->id,
                'garment_type' => 'suit',
                'description' => 'Modern slim-fit suit',
                'quantity' => 1,
                'unit_price' => 1100.00,
                'style_notes' => 'Contemporary cut, narrow lapels',
                'fabric_details' => 'Cotton-linen blend',
                'color' => 'Light grey',
                'lining_details' => 'Contrast patterned lining',
                'button_details' => 'Modern metal buttons',
                'monogram_text' => 'ER',
                'urgent_flag' => false,
            ],
            // Order 5 - David Thompson's seasonal update
            [
                'order_id' => $insertedOrders[4]->id,
                'garment_type' => 'suit',
                'description' => 'Seasonal business suit',
                'quantity' => 2,
                'unit_price' => 950.00,
                'style_notes' => 'Classic fit, versatile styling',
                'fabric_details' => 'Tropical weight wool',
                'color' => 'Medium grey',
                'lining_details' => 'Standard polyester lining',
                'button_details' => 'Plastic buttons',
                'monogram_text' => 'DT',
                'urgent_flag' => false,
            ],
        ];

        OrderItem::insert($orderItems);
    }
}
