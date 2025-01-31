@component('mail::message')
# Seu Pedido de Viagem foi atualizado!

Olá {{ $order->user->name }},

O status do seu pedido de viagem para **{{ $order->destination }}** foi atualizado para **{{ ucfirst($order->status) }}**.

@if($order->status === 'approved')
🎉 Parabéns! Sua viagem foi aprovada.
@elseif($order->status === 'canceled')
😞 Infelizmente, seu pedido foi cancelado.
@endif

Atenciosamente,  
Equipe de Viagens Onfly
@endcomponent